<?php

namespace App\Services;

use App\Contracts\CheckoutGateway;
use App\Models\Booking;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class PayPalCheckoutGateway implements CheckoutGateway
{
    public function start(Booking $booking): string
    {
        $order = $this->request('post', '/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'custom_id' => (string) $booking->id,
                'description' => $booking->depositLabel().' booking deposit · '.$booking->packageName().' · '.$booking->starts_at->timezone(config('app.timezone'))->format('D j M Y, H:i'),
                'amount' => [
                    'currency_code' => 'GBP',
                    'value' => number_format($booking->onlineChargePence() / 100, 2, '.', ''),
                ],
            ]],
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'brand_name' => config('school.name'),
                        'locale' => 'en-GB',
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('booking.success'),
                        'cancel_url' => route('booking.cancel', ['token' => $booking->public_token]),
                    ],
                ],
            ],
        ]);

        $orderId = (string) ($order['id'] ?? '');
        $url = collect($order['links'] ?? [])->first(fn (array $link) => in_array($link['rel'] ?? '', ['payer-action', 'approve'], true))['href'] ?? null;

        if ($orderId === '' || ! is_string($url) || $url === '') {
            throw new RuntimeException('PayPal did not return a payment link.');
        }

        $booking->forceFill(['stripe_session_id' => $orderId])->save();

        return $url;
    }

    public function paidBookingId(string $sessionId): ?int
    {
        if ($sessionId === '') {
            return null;
        }

        $order = $this->request('get', '/v2/checkout/orders/'.$sessionId);

        if (($order['status'] ?? '') === 'APPROVED') {
            $order = $this->capture($sessionId);
        }

        if (($order['status'] ?? '') !== 'COMPLETED') {
            return null;
        }

        $customId = $order['purchase_units'][0]['custom_id'] ?? null;

        if (is_numeric($customId)) {
            return (int) $customId;
        }

        $id = Booking::query()->where('stripe_session_id', $sessionId)->value('id');

        return $id ? (int) $id : null;
    }

    public function settleOpenOrders(): void
    {
        $bookings = Booking::query()
            ->where('status', 'pending')
            ->whereNotNull('stripe_session_id')
            ->latest('id')
            ->limit(20)
            ->get();

        foreach ($bookings as $booking) {
            try {
                $id = $this->paidBookingId((string) $booking->stripe_session_id);

                if ($id) {
                    Booking::query()->find($id)?->markPaid();
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }

    public function interpretWebhook(string $payload, ?string $signature): array
    {
        return ['action' => 'ignore', 'id' => null];
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $body = []): array
    {
        $pending = Http::withToken($this->token())
            ->acceptJson()
            ->timeout(20);

        $response = $method === 'get'
            ? $pending->get($this->base().$path)
            : $pending->post($this->base().$path, $body);

        return $this->json($response);
    }

    /**
     * @return array<string, mixed>
     */
    private function capture(string $orderId): array
    {
        $response = Http::withToken($this->token())
            ->acceptJson()
            ->withBody('{}', 'application/json')
            ->timeout(20)
            ->post($this->base().'/v2/checkout/orders/'.$orderId.'/capture');

        if ($response->status() === 422 && $response->json('details.0.issue') === 'ORDER_ALREADY_CAPTURED') {
            return $this->request('get', '/v2/checkout/orders/'.$orderId);
        }

        return $this->json($response);
    }

    private function token(): string
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');

        if (! is_string($clientId) || $clientId === '' || ! is_string($secret) || $secret === '') {
            throw new RuntimeException('PayPal keys are not set.');
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->timeout(20)
            ->post($this->base().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        $token = $this->json($response)['access_token'] ?? null;

        if (! is_string($token) || $token === '') {
            throw new RuntimeException('PayPal login failed.');
        }

        return $token;
    }

    /**
     * @return array<string, mixed>
     */
    private function json(Response $response): array
    {
        if (! $response->successful()) {
            throw new RuntimeException('PayPal request failed.');
        }

        $json = $response->json();

        return is_array($json) ? $json : [];
    }

    private function base(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }
}

<?php

namespace App\Http\Controllers;

use App\Contracts\CheckoutGateway;
use App\Models\Booking;
use App\Services\SlotAvailability;
use App\Support\Postcode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class BookingController extends Controller
{
    public function create(Request $request): View
    {
        $requested = $request->query('package');
        $locked = is_string($requested) && array_key_exists($requested, config('school.packages'));
        $package = $locked ? $requested : 'lesson';
        [$postcode, $postcodeOther] = $this->postcodeChoice((string) $request->query('postcode', ''), (string) $request->query('postcode_other', ''));

        return view('book', [
            'selectedPackage' => $package,
            'lockedPackage' => $locked,
            'postcode' => $postcode,
            'postcodeOther' => $postcodeOther,
        ]);
    }

    public function slots(Request $request, SlotAvailability $availability): JsonResponse
    {
        $data = $request->validate([
            'package' => ['required', Rule::in(array_keys(config('school.packages')))],
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        return response()->json([
            'slots' => $availability->for($data['package'], Carbon::parse($data['date'])),
        ]);
    }

    public function store(Request $request, SlotAvailability $availability, CheckoutGateway $gateway): RedirectResponse
    {
        $data = $request->validate([
            'package' => ['required', Rule::in(array_keys(config('school.packages')))],
            'date' => ['required', 'date_format:Y-m-d'],
            'slot' => ['required', 'date_format:H:i'],
            'student_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:30'],
            'postcode' => ['required', 'string', 'max:12'],
            'postcode_other' => ['nullable', 'string', 'max:12'],
            'test_centre' => ['nullable', 'required_if:package,test', Rule::in(config('school.test_centres'))],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $rawPostcode = $data['postcode'] === 'other' ? trim((string) ($data['postcode_other'] ?? '')) : $data['postcode'];

        if ($rawPostcode === '') {
            return back()->withErrors(['postcode' => 'Enter the full postcode.'])->withInput();
        }

        $formatted = Postcode::format($rawPostcode);

        if ($formatted === null) {
            return back()->withErrors(['postcode' => 'Enter a covered area, such as WC1, or a full postcode such as N1 1AA.'])->withInput();
        }

        if (! Postcode::covers($formatted)) {
            return back()->withErrors(['postcode' => 'That postcode is outside the areas we cover.'])->withInput();
        }

        $start = Carbon::parse($data['date'].' '.$data['slot']);
        $match = collect($availability->for($data['package'], $start))->first(
            fn (array $slot) => $slot['start'] === $data['slot'] && $slot['available']
        );

        if ($match === null) {
            return back()->withErrors(['slot' => 'That time is not free. Choose another slot.'])->withInput();
        }

        $package = config('school.packages.'.$data['package']);
        $end = $start->copy()->addHours((int) $package['hours']);

        $booking = DB::transaction(function () use ($data, $formatted, $start, $end, $package) {
            if (Booking::query()->blocking($start, $end)->lockForUpdate()->exists()) {
                return null;
            }

            return Booking::query()->create([
                'public_token' => Str::random(40),
                'package' => $data['package'],
                'student_name' => $data['student_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'postcode' => $formatted,
                'test_centre' => $data['package'] === 'test' ? $data['test_centre'] : null,
                'notes' => $data['notes'] ?? null,
                'starts_at' => $start,
                'ends_at' => $end,
                'amount_pence' => (int) $package['price'],
                'deposit_pence' => (int) config('school.online_deposit_pence'),
                'status' => 'pending',
                'hold_expires_at' => now()->addMinutes((int) config('school.hold_minutes')),
            ]);
        });

        if (! $booking instanceof Booking) {
            return back()->withErrors(['slot' => 'That time was just taken. Choose another slot.'])->withInput();
        }

        try {
            $url = $gateway->start($booking);
        } catch (Throwable $exception) {
            $booking->update(['status' => 'cancelled']);
            report($exception);

            return back()->withErrors([
                'payment' => 'PayPal is not ready yet. Call '.config('school.phone_display').' to book.',
            ])->withInput();
        }

        return redirect()->away($url);
    }

    public function success(Request $request, CheckoutGateway $gateway): View
    {
        $sessionId = (string) ($request->query('token') ?: $request->query('session_id', ''));
        $booking = null;

        try {
            $id = $gateway->paidBookingId($sessionId);
            $booking = $id ? Booking::query()->find($id) : null;
        } catch (Throwable $exception) {
            report($exception);
        }

        if ($booking) {
            $booking->markPaid();
        }

        return view('booking.success', ['booking' => $booking]);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function postcodeChoice(string $postcode, string $other): array
    {
        $areas = config('school.postcodes');

        if ($postcode === 'other' || ($postcode !== '' && ! in_array($postcode, $areas, true))) {
            return ['other', $postcode === 'other' ? $other : $postcode];
        }

        return [$postcode, ''];
    }

    public function cancel(string $token): View
    {
        $booking = Booking::query()->where('public_token', $token)->firstOrFail();

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
        }

        return view('booking.cancel', ['booking' => $booking]);
    }
}

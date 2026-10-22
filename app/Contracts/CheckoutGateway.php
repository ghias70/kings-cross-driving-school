<?php

namespace App\Contracts;

use App\Models\Booking;

interface CheckoutGateway
{
    public function start(Booking $booking): string;

    public function paidBookingId(string $sessionId): ?int;

    public function settleOpenOrders(): void;

    /**
     * @return array{action: string, id: int|null}
     */
    public function interpretWebhook(string $payload, ?string $signature): array;
}

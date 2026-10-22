<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class SlotAvailability
{
    /**
     * @return array<int, array{start: string, end: string, label: string, available: bool}>
     */
    public function for(string $package, CarbonInterface $date): array
    {
        $details = config('school.packages.'.$package);

        if (! is_array($details)) {
            return [];
        }

        $day = Carbon::parse($date->toDateString())->startOfDay();
        $today = now()->startOfDay();

        if ($day->lt($today) || $day->gt($today->copy()->addDays((int) config('school.hours.horizon_days')))) {
            return [];
        }

        if (! in_array($day->dayOfWeekIso, config('school.hours.days'), true)) {
            return [];
        }

        $open = Carbon::parse($day->toDateString().' '.config('school.hours.open'));
        $close = Carbon::parse($day->toDateString().' '.config('school.hours.close'));
        $duration = (int) $details['hours'];
        $interval = (int) config('school.hours.interval_minutes');
        $notice = now()->addHours((int) config('school.hours.min_notice_hours'));
        $slots = [];

        for ($cursor = $open->copy(); $cursor->copy()->addHours($duration)->lte($close); $cursor->addMinutes($interval)) {
            if ($cursor->lt($notice)) {
                continue;
            }

            $end = $cursor->copy()->addHours($duration);

            $slots[] = [
                'start' => $cursor->format('H:i'),
                'end' => $end->format('H:i'),
                'label' => $cursor->format('g:ia').' – '.$end->format('g:ia'),
                'available' => ! Booking::query()->blocking($cursor, $end)->exists(),
            ];
        }

        return $slots;
    }
}

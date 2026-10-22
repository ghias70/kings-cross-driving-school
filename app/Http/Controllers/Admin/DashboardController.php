<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Enquiry;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $todayStart = today()->startOfDay();
        $todayEnd = today()->endOfDay();

        return view('admin.dashboard', [
            'lessonsToday' => Booking::query()
                ->where('status', 'paid')
                ->whereBetween('starts_at', [$todayStart, $todayEnd])
                ->count(),
            'pendingDeposits' => Booking::query()
                ->where('status', 'pending')
                ->where('hold_expires_at', '>', now())
                ->count(),
            'upcomingPaid' => Booking::query()
                ->where('status', 'paid')
                ->where('starts_at', '>', now())
                ->count(),
            'unreadEnquiries' => Enquiry::query()->whereNull('read_at')->count(),
            'todayBookings' => Booking::query()
                ->where('status', 'paid')
                ->whereBetween('starts_at', [$todayStart, $todayEnd])
                ->orderBy('starts_at')
                ->get(),
            'upcoming' => Booking::query()
                ->where('status', 'paid')
                ->where('starts_at', '>', $todayEnd)
                ->orderBy('starts_at')
                ->limit(6)
                ->get(),
            'recentEnquiries' => Enquiry::query()->latest()->limit(5)->get(),
            'statuses' => Booking::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }
}

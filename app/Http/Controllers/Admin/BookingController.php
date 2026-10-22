<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $search = trim($request->string('q')->toString());

        $bookings = Booking::query()
            ->when(in_array($status, ['pending', 'paid', 'cancelled', 'expired'], true), fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('student_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('postcode', 'like', "%{$search}%");
                });
            })
            ->latest('starts_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(Booking $booking): View
    {
        return view('admin.bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'paid', 'cancelled', 'expired'])],
        ]);

        $payload = ['status' => $data['status']];

        if ($data['status'] === 'paid' && $booking->paid_at === null) {
            $payload['paid_at'] = now();
        }

        if (in_array($data['status'], ['cancelled', 'expired'], true)) {
            $payload['hold_expires_at'] = now();
        }

        $booking->update($payload);

        return back()->with('status', 'Booking '.$booking->invoiceNumber().' is now '.$data['status'].'.');
    }
}

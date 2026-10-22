@extends('admin.layout')

@section('title', 'Bookings')
@section('heading', 'Bookings')
@section('subheading', 'Paid lessons, open holds, and cancelled slots.')

@section('content')
    <form method="get" action="{{ route('admin.bookings.index') }}" class="mb-5 grid gap-3 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:grid-cols-[1fr_180px_auto]">
        <input class="rounded-xl border border-lane-line px-3 py-2.5 text-sm outline-none focus:border-signal" type="search" name="q" value="{{ $search }}" placeholder="Search name, phone, email, postcode">
        <select class="rounded-xl border border-lane-line px-3 py-2.5 text-sm outline-none focus:border-signal" name="status">
            <option value="">All statuses</option>
            @foreach (['pending', 'paid', 'cancelled', 'expired'] as $option)
                <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-ink px-4 py-2.5 text-sm font-bold text-white" type="submit">Filter</button>
    </form>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5">
        <div class="hidden grid-cols-[1.2fr_1fr_1fr_100px_90px] gap-3 border-b border-lane-line bg-lane px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500 md:grid">
            <span>Student</span>
            <span>When</span>
            <span>Package</span>
            <span>Status</span>
            <span></span>
        </div>
        @forelse ($bookings as $booking)
            <div class="grid gap-2 border-b border-lane-line px-5 py-4 last:border-0 md:grid-cols-[1.2fr_1fr_1fr_100px_90px] md:items-center md:gap-3">
                <div>
                    <p class="font-bold text-ink">{{ $booking->student_name }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->invoiceNumber() }} · {{ $booking->phone }}</p>
                </div>
                <div>
                    <p class="font-semibold text-ink">{{ $booking->starts_at->format('D j M') }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->starts_at->format('g:i a') }} – {{ $booking->ends_at->format('g:i a') }}</p>
                </div>
                <div>
                    <p class="font-semibold text-ink">{{ $booking->packageName() }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->depositLabel() }} deposit · {{ $booking->postcode }}</p>
                </div>
                <span class="inline-flex w-fit rounded-full px-2.5 py-1 text-xs font-bold uppercase tracking-wide
                    {{ $booking->status === 'paid' ? 'bg-emerald-50 text-emerald-800' : '' }}
                    {{ $booking->status === 'pending' ? 'bg-amber-50 text-amber-800' : '' }}
                    {{ in_array($booking->status, ['cancelled', 'expired'], true) ? 'bg-rose-50 text-rose-800' : '' }}
                ">{{ $booking->status }}</span>
                <a class="text-sm font-bold text-signal hover:underline" href="{{ route('admin.bookings.show', $booking) }}">Open</a>
            </div>
        @empty
            <p class="px-5 py-10 text-sm text-slate-600">No bookings match that filter.</p>
        @endforelse
    </div>

    <div class="mt-5">{{ $bookings->links() }}</div>
@endsection

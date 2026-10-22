@extends('admin.layout')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Today’s diary and what still needs a deposit.')

@section('content')
    @php
        $cards = [
            [
                'Lessons today',
                $lessonsToday,
                route('admin.bookings.index', ['status' => 'paid']),
                'bg-sky-50 text-sky-700',
                '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>',
            ],
            [
                'Waiting for deposit',
                $pendingDeposits,
                route('admin.bookings.index', ['status' => 'pending']),
                'bg-amber-50 text-amber-700',
                '<circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>',
            ],
            [
                'Upcoming paid',
                $upcomingPaid,
                route('admin.bookings.index', ['status' => 'paid']),
                'bg-emerald-50 text-emerald-700',
                '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
            ],
            [
                'Unread enquiries',
                $unreadEnquiries,
                route('admin.enquiries.index'),
                'bg-rose-50 text-rose-700',
                '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 7l8 6 8-6"/>',
            ],
        ];

        $statusMeta = [
            'paid' => ['bg-emerald-400', 'Confirmed'],
            'pending' => ['bg-amber-400', 'Awaiting deposit'],
            'cancelled' => ['bg-rose-400', 'Cancelled'],
            'expired' => ['bg-slate-400', 'Expired'],
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as [$label, $value, $href, $tone, $icon])
            <a href="{{ $href }}" class="group flex items-center gap-4 rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-[#ff303f]">
                <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $tone }} transition group-hover:scale-105">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">{!! $icon !!}</svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-medium text-slate-500">{{ $label }}</span>
                    <span class="mt-1 block text-3xl font-bold tracking-tight text-[#07182d]">{{ $value }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-5">
        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 xl:col-span-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-[#07182d]">Today’s lessons</h2>
                </div>
                <a class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#ff303f] hover:underline" href="{{ route('admin.bookings.index') }}">
                    All bookings
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="mt-4 divide-y divide-[#dde6f1]">
                @forelse ($todayBookings as $booking)
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="flex flex-wrap items-center justify-between gap-3 py-4 first:pt-0 last:pb-0 hover:bg-[#f3f7fc]">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#f3f7fc] text-[#07182d]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/><circle cx="12" cy="12" r="9"/></svg>
                            </span>
                            <div>
                                <p class="font-bold text-[#07182d]">{{ $booking->starts_at->format('g:i a') }} · {{ $booking->student_name }}</p>
                                <p class="text-sm text-slate-500">{{ $booking->packageName() }} · {{ $booking->postcode }} · {{ $booking->invoiceNumber() }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-emerald-800">{{ $booking->diaryStatus() }}</span>
                    </a>
                @empty
                    <div class="flex flex-col items-center rounded-2xl bg-[#f3f7fc] px-4 py-10 text-center">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/></svg>
                        </span>
                        <p class="mt-3 text-sm font-medium text-slate-600">No paid lessons in the diary for today.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl bg-[#07182d] p-6 text-white shadow-sm xl:col-span-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </span>
                <div>
                    <h2 class="text-lg font-bold">Booking status</h2>
                    <p class="text-sm text-white/65">All bookings in the system.</p>
                </div>
            </div>
            <div class="mt-5 space-y-3">
                @forelse ($statuses as $status => $count)
                    @php($meta = $statusMeta[$status] ?? ['bg-slate-400', ucfirst($status)])
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <span class="inline-flex items-center gap-2 text-sm font-semibold">
                            <span class="h-2.5 w-2.5 rounded-full {{ $meta[0] }}"></span>
                            {{ $meta[1] }}
                        </span>
                        <span class="text-xl font-bold">{{ $count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-white/70">Bookings will show here once guests start booking.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 7l5 5-5 5"/></svg>
                </span>
                <h2 class="text-lg font-bold text-[#07182d]">Coming up</h2>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($upcoming as $booking)
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center gap-3 rounded-2xl border border-[#dde6f1] px-4 py-3 hover:border-[#ff303f]">
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#f3f7fc] text-[#07182d]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21v-1a6 6 0 0112 0v1"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block font-bold text-[#07182d]">{{ $booking->starts_at->format('D j M · g:i a') }}</span>
                            <span class="block text-sm text-slate-500">{{ $booking->student_name }} · {{ $booking->packageName() }}</span>
                        </span>
                    </a>
                @empty
                    <div class="flex flex-col items-center rounded-2xl bg-[#f3f7fc] px-4 py-10 text-center">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 7l5 5-5 5"/></svg>
                        </span>
                        <p class="mt-3 text-sm font-medium text-slate-600">No later paid lessons yet.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 7l8 6 8-6"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-[#07182d]">Latest enquiries</h2>
                </div>
                <a class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#ff303f] hover:underline" href="{{ route('admin.enquiries.index') }}">
                    View all
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentEnquiries as $enquiry)
                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="flex items-center gap-3 rounded-2xl border border-[#dde6f1] px-4 py-3 hover:border-[#ff303f]">
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21v-1a6 6 0 0112 0v1"/></svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center gap-2">
                                <span class="font-bold text-[#07182d]">{{ $enquiry->name }}</span>
                                @if ($enquiry->read_at === null)
                                    <span class="rounded-full bg-[#ffe4e7] px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide text-[#ff303f]">New</span>
                                @endif
                            </span>
                            <span class="block text-sm text-slate-500">{{ $enquiry->phone }} · {{ $enquiry->created_at->diffForHumans() }}</span>
                        </span>
                    </a>
                @empty
                    <div class="flex flex-col items-center rounded-2xl bg-[#f3f7fc] px-4 py-10 text-center">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 7l8 6 8-6"/></svg>
                        </span>
                        <p class="mt-3 text-sm font-medium text-slate-600">Contact form messages will land here.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection

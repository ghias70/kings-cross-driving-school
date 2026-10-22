@extends('admin.layout')

@section('title', $booking->invoiceNumber())
@section('heading', $booking->invoiceNumber())
@section('subheading', $booking->packageName().' · '.$booking->diaryStatus())

@section('content')
    <div class="mb-5">
        <a class="text-sm font-semibold text-signal hover:underline" href="{{ route('admin.bookings.index') }}">← Back to bookings</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.8fr]">
        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Student</dt>
                    <dd class="mt-1 text-lg font-bold text-ink">{{ $booking->student_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Phone</dt>
                    <dd class="mt-1"><a class="font-semibold text-ink underline" href="tel:{{ $booking->phone }}">{{ $booking->phone }}</a></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Email</dt>
                    <dd class="mt-1 break-all"><a class="font-semibold text-ink underline" href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Postcode</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $booking->postcode }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">When</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $booking->starts_at->format('l j F Y') }}<br>{{ $booking->starts_at->format('g:i a') }} – {{ $booking->ends_at->format('g:i a') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Test centre</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $booking->test_centre ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Money</dt>
                    <dd class="mt-1 font-semibold text-ink">Deposit {{ $booking->depositLabel() }} · Balance {{ $booking->balanceLabel() }} · Total {{ $booking->priceLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Payment ref</dt>
                    <dd class="mt-1 break-all text-sm font-semibold text-ink">{{ $booking->stripe_session_id ?: '—' }}</dd>
                </div>
            </dl>
            @if ($booking->notes)
                <div class="mt-6 rounded-2xl bg-lane px-4 py-3 text-sm text-ink">
                    <strong>Notes.</strong> {{ $booking->notes }}
                </div>
            @endif
        </section>

        <section class="rounded-3xl bg-ink p-6 text-white shadow-sm">
            <h2 class="text-lg font-bold">Update status</h2>
            <p class="mt-1 text-sm text-white/65">Current: <span class="font-semibold capitalize text-white">{{ $booking->status }}</span></p>
            <form class="mt-5 space-y-3" method="post" action="{{ route('admin.bookings.update', $booking) }}">
                @csrf
                @method('PATCH')
                @foreach (['paid' => 'Mark paid / confirmed', 'pending' => 'Waiting for deposit', 'cancelled' => 'Cancel booking', 'expired' => 'Mark expired'] as $value => $label)
                    <button class="w-full rounded-xl px-4 py-3 text-left text-sm font-bold {{ $booking->status === $value ? 'bg-signal text-white' : 'bg-white/10 hover:bg-white/15' }}" type="submit" name="status" value="{{ $value }}">{{ $label }}</button>
                @endforeach
            </form>
        </section>
    </div>
@endsection

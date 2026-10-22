@extends('layouts.site')

@section('title', 'Payment cancelled')
@section('description', 'Booking payment cancelled')

@section('content')
    <section class="section">
        <div class="container invoice-wrap">
            <div class="simple-panel invoice-empty">
                <h1>Payment cancelled</h1>
                @if ($booking->status === 'paid')
                    <p>This lesson is already confirmed, so the time is still yours.</p>
                @else
                    <p>The hold on {{ $booking->starts_at->timezone(config('app.timezone'))->format('l j F, g:ia') }} has been released. You can pick another time.</p>
                @endif
                <a class="btn btn--primary" href="{{ route('book', ['package' => $booking->package]) }}">Choose another slot</a>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.site')

@section('title', $booking ? 'Invoice '.$booking->invoiceNumber() : 'Payment not confirmed')
@section('description', 'Booking confirmation for '.config('school.name'))

@section('content')
    <section class="section">
        <div class="container invoice-wrap">
            @if ($booking && $booking->status === 'paid')
                @php
                    $start = $booking->starts_at->timezone(config('app.timezone'));
                    $end = $booking->ends_at->timezone(config('app.timezone'));
                    $paidOn = ($booking->paid_at ?? $booking->updated_at)->timezone(config('app.timezone'));
                    $hours = (int) config('school.packages.'.$booking->package.'.hours');
                @endphp
                <div class="invoice-toolbar no-print">
                    <p>Show this invoice to your instructor.</p>
                    <button class="btn btn--primary" type="button" onclick="window.print()">Print or save</button>
                </div>
                <article class="simple-panel invoice-sheet">
                    <div class="invoice-head">
                        <div>
                            <p class="eyebrow">Invoice</p>
                            <h1>{{ config('school.name') }}</h1>
                            <p>{{ config('school.phone_display') }}</p>
                            <p>{{ config('school.email') }}</p>
                        </div>
                        <div class="invoice-head__meta">
                            <p><strong>{{ $booking->invoiceNumber() }}</strong></p>
                            <p>{{ $paidOn->format('j F Y') }}</p>
                            <p class="invoice-paid">DEPOSIT PAID</p>
                        </div>
                    </div>

                    <div class="invoice-grid">
                        <div>
                            <p class="eyebrow">Pupil</p>
                            <p><strong>{{ $booking->student_name }}</strong></p>
                            <p>{{ $booking->phone }}</p>
                            <p>{{ $booking->email }}</p>
                        </div>
                        <div>
                            <p class="eyebrow">Lesson</p>
                            <p><strong>{{ $start->format('l j F Y') }}</strong></p>
                            <p>{{ $start->format('g:ia') }} to {{ $end->format('g:ia') }}</p>
                            <p>Pickup {{ $booking->postcode }}</p>
                        </div>
                    </div>

                    <div class="invoice-lines">
                        <div class="invoice-line">
                            <div>
                                <p><strong>{{ $booking->packageName() }}</strong></p>
                                <p>{{ $hours }} hours · manual car</p>
                                @if ($booking->test_centre)
                                    <p>Test centre: {{ $booking->test_centre }}</p>
                                @endif
                            </div>
                            <p><strong>{{ $booking->priceLabel() }}</strong></p>
                        </div>
                        <div class="invoice-breakdown">
                            <div><span>Deposit paid online</span><strong>{{ $booking->depositLabel() }}</strong></div>
                            <div><span>Due before lesson</span><strong>{{ $booking->balanceLabel() }}</strong></div>
                            <div class="invoice-breakdown__total"><span>Paid online today</span><strong>{{ $booking->depositLabel() }}</strong></div>
                        </div>
                    </div>

                    <p class="form-note">The £{{ number_format($booking->deposit_pence / 100, 0) }} deposit is paid. Pay the remaining {{ $booking->balanceLabel() }} to your instructor before the lesson starts. Keep this invoice and show it at the lesson.</p>
                </article>
                <a class="btn btn--primary no-print" href="{{ route('home') }}">Back to the homepage</a>
            @else
                <div class="simple-panel invoice-empty">
                    <h1>Payment not confirmed</h1>
                    <p>This payment is not confirmed yet. If money left your account, call {{ config('school.phone_display') }} and quote your name.</p>
                    <a class="btn btn--primary" href="{{ route('home') }}">Back to the homepage</a>
                </div>
            @endif
        </div>
    </section>
@endsection

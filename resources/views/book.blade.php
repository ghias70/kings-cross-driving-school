@extends('layouts.site')

@section('title', 'Book a driving lesson | '.config('school.name'))
@section('description', 'Book a manual driving lesson in London. Choose a date and a free time, then pay a £10 deposit online with PayPal. Pay the rest to your instructor before the lesson starts.')

@section('content')
    <section class="page-banner" style="--page-hero: url('{{ asset('images/page-hero.jpg') }}')">
        <div class="container">
            <p class="eyebrow eyebrow--light">Book</p>
            <h1>Pick a slot and pay £{{ number_format(config('school.online_deposit_pence') / 100, 0) }} online</h1>
            <p>{{ config('school.hours.label') }}. A time is held for {{ config('school.hold_minutes') }} minutes while you pay the £{{ number_format(config('school.online_deposit_pence') / 100, 0) }} deposit on PayPal. The remaining lesson price is paid to your instructor before the lesson starts.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <form id="booking-form" method="post" action="{{ route('book.store') }}" data-slots-url="{{ route('book.slots') }}" class="booking-layout">
                @csrf
                <div class="simple-panel booking-panel">
                    @if ($errors->any())
                        <div class="form-errors">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="booking-fields">
                        @php $chosen = config('school.packages.'.old('package', $selectedPackage)); @endphp
                        @if ($lockedPackage)
                            <div>
                                <span class="field-label">Lesson</span>
                                <p class="field-static">{{ $chosen['name'] }} · £{{ number_format($chosen['price'] / 100, 0) }} · {{ $chosen['hours'] }} hours</p>
                                <input type="hidden" name="package" value="{{ old('package', $selectedPackage) }}">
                            </div>
                        @else
                            <label>
                                <span class="field-label">Lesson</span>
                                <select name="package">
                                    @foreach (config('school.packages') as $slug => $package)
                                        <option value="{{ $slug }}" @selected(old('package', $selectedPackage) === $slug)>{{ $package['name'] }} · £{{ number_format($package['price'] / 100, 0) }} · {{ $package['hours'] }} hours</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif

                        <label>
                            <span class="field-label">Date</span>
                            <input type="date" name="date" value="{{ old('date') }}" min="{{ now()->toDateString() }}" max="{{ now()->addDays(config('school.hours.horizon_days'))->toDateString() }}" required>
                        </label>

                        @php
                            $chosenPostcode = old('postcode', $postcode);
                            $typedPostcode = old('postcode_other', $postcodeOther);
                        @endphp
                        <label>
                            <span class="field-label">Postcode</span>
                            <select name="postcode" required>
                                <option value="">Choose a postcode</option>
                                @foreach (config('school.postcodes') as $code)
                                    <option value="{{ $code }}" @selected($chosenPostcode === $code)>{{ $code }}</option>
                                @endforeach
                                <option value="other" @selected($chosenPostcode === 'other')>Other</option>
                            </select>
                        </label>

                        <label id="postcode-other-field" class="{{ $chosenPostcode === 'other' ? '' : 'is-hidden' }}">
                            <span class="field-label">Full postcode</span>
                            <input id="postcode-other" name="postcode_other" value="{{ $typedPostcode }}" placeholder="N1 1AA">
                        </label>

                        <div id="test-centre-field" class="booking-fields__full {{ old('package', $selectedPackage) === 'test' ? '' : 'is-hidden' }}">
                            <label>
                                <span class="field-label">Test centre</span>
                                <select name="test_centre">
                                    <option value="">Choose a centre</option>
                                    @foreach (config('school.test_centres') as $centre)
                                        <option value="{{ $centre }}" @selected(old('test_centre') === $centre)>{{ $centre }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="booking-fields__full">
                            <p class="field-label">Free times</p>
                            <div id="slot-list" class="slot-list"></div>
                            <noscript><p class="form-note">Turn on JavaScript to see times, or call {{ config('school.phone_display') }}.</p></noscript>
                        </div>

                        <p class="booking-fields__full booking-subtitle">Your details</p>
                        <label>
                            <span class="field-label">Your name</span>
                            <input name="student_name" value="{{ old('student_name') }}" placeholder="Full name" required>
                        </label>
                        <label>
                            <span class="field-label">Phone</span>
                            <input name="phone" value="{{ old('phone') }}" placeholder="07XXX XXXXXX" required>
                        </label>
                        <label>
                            <span class="field-label">Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required>
                        </label>
                        <label class="booking-fields__full">
                            <span class="field-label">Notes</span>
                            <textarea name="notes" rows="2" placeholder="Optional">{{ old('notes') }}</textarea>
                        </label>
                    </div>
                </div>

                @php
                    $summaryPackage = config('school.packages.'.old('package', $selectedPackage));
                    $summaryDate = old('date');
                    $summaryPostcode = old('postcode', $postcode);
                    if ($summaryPostcode === 'other') {
                        $summaryPostcode = old('postcode_other', $postcodeOther);
                    }
                    $deposit = (int) config('school.online_deposit_pence');
                @endphp
                <aside class="simple-panel booking-summary">
                    <h2>Booking summary</h2>
                    <dl class="summary-list">
                        <div>
                            <dt>Lesson</dt>
                            <dd id="summary-lesson">{{ $summaryPackage['name'] }}<br><span>{{ $summaryPackage['hours'] }} hours · Manual</span></dd>
                        </div>
                        <div id="summary-date-row" class="{{ $summaryDate ? '' : 'is-hidden' }}">
                            <dt>Date</dt>
                            <dd id="summary-date">{{ $summaryDate ? \Carbon\Carbon::parse($summaryDate)->format('D, j M Y') : '' }}</dd>
                        </div>
                        <div>
                            <dt>Time</dt>
                            <dd id="summary-time">Choose a time</dd>
                        </div>
                        <div id="summary-postcode-row" class="{{ $summaryPostcode ? '' : 'is-hidden' }}">
                            <dt>Postcode</dt>
                            <dd id="summary-postcode">{{ $summaryPostcode }}</dd>
                        </div>
                    </dl>
                    <div class="summary-totals">
                        <div>
                            <span>Lesson price</span>
                            <strong id="summary-lesson-price">£{{ number_format($summaryPackage['price'] / 100, 0) }}</strong>
                        </div>
                        <div>
                            <span>Pay online now</span>
                            <strong id="summary-deposit">£{{ number_format($deposit / 100, 0) }}</strong>
                        </div>
                        <div>
                            <span>Due before lesson</span>
                            <strong id="summary-balance">£{{ number_format(($summaryPackage['price'] - $deposit) / 100, 0) }}</strong>
                        </div>
                        <div class="summary-totals__pay">
                            <span>Deposit today</span>
                            <strong id="summary-total">£{{ number_format($deposit / 100, 0) }}</strong>
                        </div>
                    </div>
                    <button class="btn btn--primary" type="submit">Pay £{{ number_format($deposit / 100, 0) }} with PayPal</button>
                    <p class="form-note">The time is held for {{ config('school.hold_minutes') }} minutes. After you pay the deposit, wait until you are back on this site. Pay the remaining balance to your instructor before the lesson starts.</p>
                </aside>

                @php
                    $depositPounds = (int) (config('school.online_deposit_pence') / 100);
                    $bookingPackagesJson = collect(config('school.packages'))->map(function ($package) use ($depositPounds) {
                        $price = (int) ($package['price'] / 100);

                        return [
                            'name' => $package['name'],
                            'hours' => $package['hours'],
                            'price' => $price,
                            'deposit' => $depositPounds,
                            'balance' => $price - $depositPounds,
                        ];
                    })->all();
                @endphp
                <script type="application/json" id="booking-packages">@json($bookingPackagesJson)</script>
            </form>
        </div>
    </section>
@endsection

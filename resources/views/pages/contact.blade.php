@extends('layouts.site')

@section('title', 'Contact | '.config('school.name'))
@section('description', 'Contact King\'s Cross Driving School. For a confirmed slot, use the booking page and pay a £10 deposit online.')

@section('content')
    <section class="page-banner" style="--page-hero: url('{{ asset('images/page-hero.jpg') }}')">
        <div class="container">
            <p class="eyebrow eyebrow--light">Contact</p>
            <h1>Get in touch</h1>
            <p>Call, WhatsApp, or send a message. To keep a time online, use the booking page and pay a £10 deposit. The remaining lesson price is paid before the lesson starts.</p>
        </div>
    </section>

    <section class="section section--contact">
        <div class="container contact-layout">
            <aside class="contact-info">
                <h2>Contact details</h2>
                <ul class="contact-info__list">
                    <li>
                        <span>Phone</span>
                        <a href="tel:{{ config('school.phone_tel') }}">{{ config('school.phone_display') }}</a>
                    </li>
                    <li>
                        <span>Email</span>
                        <a href="mailto:{{ config('school.email') }}">{{ config('school.email') }}</a>
                    </li>
                    <li>
                        <span>WhatsApp</span>
                        <a href="https://wa.me/{{ config('school.whatsapp') }}" target="_blank" rel="noopener">{{ config('school.phone_display') }}</a>
                    </li>
                    <li>
                        <span>Address</span>
                        <strong>{{ config('school.address') }}</strong>
                    </li>
                    <li>
                        <span>Hours</span>
                        <strong>{{ config('school.hours_label') }}</strong>
                    </li>
                    <li>
                        <span>Prices</span>
                        <strong>{{ config('school.price_line') }}</strong>
                    </li>
                </ul>
                <a class="btn btn--primary" href="{{ route('book') }}">Book a lesson</a>
            </aside>

            <div class="contact-panel">
                <h2>Send a message</h2>
                <p class="contact-panel__lead">Tell us what you need and we will reply by phone.</p>

                @if (session('status'))
                    <p class="form-status">{{ session('status') }}</p>
                @endif

                <form method="post" action="{{ route('contact.store') }}" class="contact-form">
                    @csrf
                    <label>
                        <span>Name</span>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="120">
                        @error('name') <em>{{ $message }}</em> @enderror
                    </label>
                    <label>
                        <span>Phone</span>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="40">
                        @error('phone') <em>{{ $message }}</em> @enderror
                    </label>
                    <label>
                        <span>Email (optional)</span>
                        <input type="email" name="email" value="{{ old('email') }}" maxlength="160">
                        @error('email') <em>{{ $message }}</em> @enderror
                    </label>
                    <label>
                        <span>Lesson</span>
                        <select name="package">
                            <option value="">Choose a lesson</option>
                            @foreach (config('school.packages') as $slug => $pkg)
                                <option value="{{ $slug }}" @selected(old('package', $package) === $slug)>{{ $pkg['name'] }} · £{{ number_format($pkg['price'] / 100, 0) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="contact-form__full">
                        <span>Postcode (optional)</span>
                        <input type="text" name="postcode" value="{{ old('postcode') }}" maxlength="16" placeholder="e.g. N1 1AA">
                    </label>
                    <label class="contact-form__full">
                        <span>Message (optional)</span>
                        <textarea name="message" rows="3" maxlength="2000">{{ old('message') }}</textarea>
                    </label>
                    <div class="contact-form__actions">
                        <button class="btn btn--primary" type="submit">Send message</button>
                        <p class="form-note">To lock in a slot online, use the <a href="{{ route('book') }}">booking page</a> and pay the £10 deposit.</p>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

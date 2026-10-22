@extends('layouts.site')

@section('title', "Manual driving lessons in London | ".config('school.name'))
@section('description', config('school.tagline'))

@section('content')
    <section class="hero" style="--hero-image: url('{{ asset('images/hero-car.png') }}')">
        <div class="container hero__inner">
            <div class="hero__copy">
                <p class="eyebrow">Professional driving lessons in London</p>
                <h1>
                    Learn to Drive with
                    <span class="hero__accent">King's Cross</span>
                    Driving School
                </h1>
                <p class="hero__lead">Safe, friendly and professional driving lessons with a high first-time pass rate.</p>
                <ul class="trust-list">
                    <li><span class="check-icon" aria-hidden="true"></span> Experienced driving instruction</li>
                    <li><span class="check-icon" aria-hidden="true"></span> Manual lessons only</li>
                    <li><span class="check-icon" aria-hidden="true"></span> Learn at your own pace</li>
                    <li><span class="check-icon" aria-hidden="true"></span> Flexible lesson times</li>
                    <li><span class="check-icon" aria-hidden="true"></span> Local London coverage</li>
                </ul>
                <div class="hero__actions">
                    <a class="btn btn--primary" href="{{ route('book') }}">
                        <img src="{{ asset('icons/calendar.svg') }}" alt="" class="btn__icon btn__icon--light">
                        Book a Lesson
                    </a>
                    <a class="btn btn--ghost" href="tel:{{ config('school.phone_tel') }}">
                        <img src="{{ asset('icons/phone.svg') }}" alt="" class="btn__icon btn__icon--light">
                        Call {{ config('school.phone_display') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits">
        <div class="container">
            <div class="benefits__grid">
                @foreach (config('school.benefits') as $index => $benefit)
                    <article class="benefit">
                        <div class="benefit__icon" aria-hidden="true">
                            <img src="{{ asset('icons/'.$benefit['icon'].'.svg') }}" alt="">
                        </div>
                        <div class="benefit__copy">
                            <p class="benefit__index">0{{ $index + 1 }}</p>
                            <h3>{{ $benefit['title'] }}</h3>
                            <p>{{ $benefit['text'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--lessons" id="lessons">
        <div class="container">
            <div class="section__intro">
                <p class="eyebrow eyebrow--blue">Driving Lessons</p>
                <h2>Choose the right lesson for you</h2>
                <p>Three fixed prices. Pay a £10 deposit online to hold your slot. Pay the remaining balance to your instructor before the lesson starts.</p>
            </div>
            <div class="lesson-grid">
                @foreach (config('school.lessons') as $lesson)
                    <x-lesson-card :lesson="$lesson" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--about" id="about">
        <div class="container about">
            <div class="about__media">
                <img src="{{ asset('images/about-car-rear.jpg') }}?v=2" alt="King's Cross Driving School black Toyota Yaris with L plates">
            </div>
            <div class="about__copy">
                <p class="eyebrow eyebrow--navy">About King's Cross Driving School</p>
                <h2>Friendly, Patient &amp; Professional</h2>
                <p>{{ config('school.about_intro') }}</p>
                <ul class="check-list check-list--solid">
                    @foreach (config('school.about_points') as $point)
                        <li>
                            <span class="check-icon check-icon--solid" aria-hidden="true"></span>
                            <span>{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="about__actions">
                    <a class="btn btn--primary" href="{{ route('about') }}">
                        About Us
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
                <div class="about__watermark" aria-hidden="true"></div>
            </div>
        </div>
    </section>

    <section class="section section--reviews" id="reviews">
        <div class="container">
            <div class="section__intro">
                <p class="eyebrow eyebrow--blue">Google reviews</p>
                <h2>What our learners say</h2>
                <p>Real five-star reviews from pupils who passed with King's Cross Driving School.</p>
            </div>
            <div class="review-grid">
                @foreach (config('school.testimonials') as $review)
                    <article class="review-card">
                        <div class="review-card__stars" aria-label="5 stars">
                            @for ($i = 0; $i < 5; $i++)
                                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10 1.5l2.47 5.01 5.53.8-4 3.9.94 5.5L10 14.9l-4.94 2.61.94-5.5-4-3.9 5.53-.8L10 1.5z"/></svg>
                            @endfor
                        </div>
                        <blockquote>“{{ $review['quote'] }}”</blockquote>
                        <footer>
                            <strong>{{ $review['name'] }}</strong>
                            @if (! empty($review['result']))
                                <span>{{ $review['result'] }}</span>
                            @endif
                        </footer>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--gallery-preview">
        <div class="container">
            <div class="section__intro section__intro--row">
                <div>
                    <p class="eyebrow eyebrow--navy">Gallery</p>
                    <h2>Pupils on test day</h2>
                    <p>Pass photos from King's Cross Driving School learners.</p>
                </div>
                <a class="btn btn--primary" href="{{ route('gallery') }}">
                    View gallery
                    <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="home-gallery">
                @foreach (['pass-01.jpg', 'pass-02.jpg', 'pass-03.jpg'] as $photo)
                    <a class="home-gallery__item" href="{{ route('gallery') }}">
                        <img src="{{ asset('images/gallery/'.$photo) }}" alt="Pupil with a pass certificate" loading="lazy">
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cta" style="--cta-image: url('{{ asset('images/cta-london-background.jpg') }}')">
        <div class="container cta__inner">
            <div class="cta__copy">
                <p class="eyebrow eyebrow--light">Ready to start?</p>
                <h2>Book Your Driving Lesson Today</h2>
                <p>Take the first step towards your driving licence. Pick a free slot, pay a £10 deposit online, and pay the remaining balance before the lesson starts.</p>
                <div class="cta__actions">
                    <a class="btn btn--primary" href="{{ route('book') }}">
                        <img src="{{ asset('icons/calendar.svg') }}" alt="" class="btn__icon btn__icon--light">
                        Book a Lesson
                    </a>
                    <a class="btn btn--ghost" href="tel:{{ config('school.phone_tel') }}">
                        <img src="{{ asset('icons/phone.svg') }}" alt="" class="btn__icon btn__icon--light">
                        Call {{ config('school.phone_display') }}
                    </a>
                </div>
            </div>
            <div class="cta-points">
                <div class="cta-point">
                    <img src="{{ asset('icons/calendar.svg') }}" alt="">
                    <span>Pick a free slot online</span>
                </div>
                <div class="cta-point">
                    <img src="{{ asset('icons/clock.svg') }}" alt="">
                    <span>£10 deposit holds the time</span>
                </div>
                <div class="cta-point">
                    <img src="{{ asset('icons/mail.svg') }}" alt="">
                    <span>Balance paid before the lesson</span>
                </div>
            </div>
        </div>
    </section>

@endsection

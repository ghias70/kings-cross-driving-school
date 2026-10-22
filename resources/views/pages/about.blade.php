@extends('layouts.site')

@section('title', 'About Us | '.config('school.name'))
@section('description', 'Friendly, patient and professional driving lessons with King\'s Cross Driving School in London.')

@section('content')
    <section class="page-banner" style="--page-hero: url('{{ asset('images/page-hero.jpg') }}')">
        <div class="container">
            <p class="eyebrow eyebrow--light">About Us</p>
            <h1>Friendly, patient &amp; professional</h1>
            <p>{{ config('school.tagline') }}</p>
        </div>
    </section>
    <section class="section section--about">
        <div class="container about">
            <div class="about__media">
                <img src="{{ asset('images/about-car-rear.jpg') }}" alt="King's Cross Driving School learner car">
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
                    <a class="btn btn--primary" href="{{ route('book') }}">
                        Book a Lesson
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
                <div class="about__watermark" aria-hidden="true"></div>
            </div>
        </div>
    </section>
@endsection

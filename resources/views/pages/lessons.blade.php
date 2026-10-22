@extends('layouts.site')

@section('title', 'Driving Lessons | '.config('school.name'))
@section('description', 'A manual driving lesson is £80 for 2 hours. The practical test is £200 for 3 hours. Pass Plus is £300 for 6 hours and includes the DVSA certificate.')

@section('content')
    <section class="page-banner" style="--page-hero: url('{{ asset('images/page-hero.jpg') }}')">
        <div class="container">
            <p class="eyebrow eyebrow--light">Driving Lessons</p>
            <h1>Manual lessons, test day, and Pass Plus</h1>
            <p>{{ config('school.price_line') }} Pay a £10 deposit online to hold your slot. Pay the rest to your instructor before the lesson starts.</p>
        </div>
    </section>
    <section class="section">
        <div class="container package-grid">
            @foreach (config('school.packages') as $slug => $package)
                <div id="{{ $slug }}">
                    <x-package-card :slug="$slug" :package="$package" />
                </div>
            @endforeach
        </div>
    </section>
@endsection

@extends('layouts.site')

@section('title', 'Areas and test centres | '.config('school.name'))
@section('description', 'Pickup postcodes and practical test centres covered by King\'s Cross Driving School.')

@section('content')
    <section class="page-banner" style="--page-hero: url('{{ asset('images/page-hero.jpg') }}')">
        <div class="container">
            <p class="eyebrow eyebrow--light">Areas</p>
            <h1>Pickup postcodes and test centres</h1>
            <p>Pickup is limited to these London postcode districts. Test day can include the drive to a covered centre.</p>
        </div>
    </section>
    <section class="section">
        <div class="container areas-grid">
            <div class="simple-panel">
                <h2>Pickup postcodes</h2>
                <div class="chip-list">
                    @foreach (config('school.postcodes') as $postcode)
                        <span class="chip">{{ $postcode }}</span>
                    @endforeach
                </div>
            </div>
            <div class="simple-panel">
                <h2>Test centres</h2>
                <ul class="plain-list">
                    @foreach (config('school.test_centres') as $centre)
                        <li>{{ $centre }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endsection

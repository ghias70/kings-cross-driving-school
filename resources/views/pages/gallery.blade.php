@extends('layouts.site')

@section('title', 'Gallery | '.config('school.name'))
@section('description', 'Photos of pupils with their pass certificates after lessons with King\'s Cross Driving School.')

@section('content')
    <section class="page-banner" style="--page-hero: url('{{ asset('images/page-hero.jpg') }}')">
        <div class="container">
            <p class="eyebrow eyebrow--light">Gallery</p>
            <h1>Pupils on test day</h1>
            <p>Photos of pupils with their pass certificates after lessons with {{ config('school.name') }}.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($photos->isEmpty())
                <div class="simple-panel">
                    <p>Gallery photos will appear here soon.</p>
                </div>
            @else
                <div class="gallery-grid">
                    @foreach ($photos as $photo)
                        <button type="button" class="gallery-item" data-gallery="{{ asset($photo) }}">
                            <img src="{{ asset($photo) }}" alt="Pupil with a pass certificate" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <dialog id="gallery-view" class="gallery-dialog">
        <form method="dialog" class="gallery-dialog__bar">
            <button class="btn btn--primary" type="submit">Close</button>
        </form>
        <img id="gallery-full" alt="Pupil with a pass certificate">
    </dialog>
@endsection

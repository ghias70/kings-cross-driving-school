@props(['lesson'])

@php
    $featured = ($lesson['slug'] ?? '') === 'lesson';
@endphp

<article @class(['lesson-card', 'lesson-card--featured' => $featured])>
    <div class="lesson-card__top">
        <div class="lesson-card__icon" aria-hidden="true">
            <img src="{{ asset('icons/'.$lesson['icon'].'.svg') }}" alt="">
        </div>
        @if ($featured)
            <p class="lesson-card__badge">Most booked</p>
        @endif
    </div>

    <h3>{{ $lesson['name'] }}</h3>
    <p class="lesson-card__text">{{ $lesson['short'] }}</p>

    <div class="lesson-card__meta">
        <span>{{ $lesson['hours'] }} hours</span>
        <span>Manual</span>
        <span>£10 deposit</span>
    </div>

    <div class="lesson-card__footer">
        <p class="lesson-card__price">
            £{{ number_format($lesson['price'] / 100, 0) }}
            <span>lesson price</span>
        </p>
        <a class="btn btn--primary lesson-card__cta" href="{{ route('book', ['package' => $lesson['slug'] ?? null]) }}">
            Book now
            <span aria-hidden="true">→</span>
        </a>
    </div>
</article>

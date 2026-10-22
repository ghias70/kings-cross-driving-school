@props(['slug', 'package'])

<article @class([
    'package-card',
    'package-card--highlight' => $package['highlight'] ?? false,
])>
    @if ($package['highlight'] ?? false)
        <p class="package-card__tag">Most booked</p>
    @endif
    <p class="package-card__badge">{{ $package['badge'] }}</p>
    <h3>{{ $package['name'] }}</h3>
    <p class="package-card__short">{{ $package['short'] }}</p>
    <p class="package-card__price">£{{ number_format($package['price'] / 100, 0) }}</p>
    <p class="package-card__hours">{{ $package['hours'] }} hours</p>
    <ul class="package-card__points">
        @foreach ($package['points'] as $point)
            <li>
                <span class="check-icon check-icon--solid" aria-hidden="true"></span>
                <span>{{ $point }}</span>
            </li>
        @endforeach
    </ul>
    <div class="package-card__action">
        <a class="btn btn--primary" href="{{ route('book', ['package' => $slug]) }}">Book a Lesson</a>
    </div>
</article>

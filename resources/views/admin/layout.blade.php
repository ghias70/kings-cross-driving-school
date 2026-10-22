<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Office') · {{ config('school.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        ink: { DEFAULT: '#07182d', soft: '#0f2744' },
                        signal: { DEFAULT: '#ff303f', soft: '#ffe4e7' },
                        lane: { DEFAULT: '#f3f7fc', line: '#dde6f1' },
                    },
                },
            },
        }
    </script>
</head>
<body class="min-h-screen bg-[#f3f7fc] font-sans text-[#07182d] antialiased">
    <div class="lg:grid lg:min-h-screen lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="flex min-h-screen flex-col bg-[#07182d] text-slate-300">
            <div class="border-b border-white/10 px-4 py-5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl bg-white px-3 py-3 text-[#07182d]">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#ff303f] text-lg font-bold text-white">L</span>
                    <span class="min-w-0">
                        <span class="block text-[11px] font-bold uppercase tracking-[0.18em] text-[#ff303f]">Office</span>
                        <span class="block truncate text-base font-bold leading-tight">King's Cross DS</span>
                    </span>
                </a>
            </div>

            <p class="px-5 pb-2 pt-5 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Menu</p>

            <nav class="flex flex-1 flex-col gap-1 px-3 pb-4">
                @php
                    $links = [
                        [
                            'route' => 'admin.dashboard',
                            'label' => 'Dashboard',
                            'active' => request()->routeIs('admin.dashboard'),
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 10v10h14V10"/>',
                        ],
                        [
                            'route' => 'admin.bookings.index',
                            'label' => 'Bookings',
                            'active' => request()->routeIs('admin.bookings.*'),
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>',
                        ],
                        [
                            'route' => 'admin.enquiries.index',
                            'label' => 'Enquiries',
                            'active' => request()->routeIs('admin.enquiries.*'),
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 7l8 6 8-6"/>',
                        ],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ $link['active'] ? 'bg-[#ff303f] text-white shadow-lg shadow-red-900/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">{!! $link['icon'] !!}</svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto space-y-1 border-t border-white/10 px-3 py-4">
                <div class="mb-3 rounded-xl bg-white/5 px-3 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Signed in</p>
                    <p class="mt-1 truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold hover:bg-white/5 hover:text-white">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7v7M10 14L21 3M5 10v11h14v-7"/></svg>
                    View website
                </a>
                <form method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-400 hover:bg-white/5 hover:text-white" type="submit">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        <div class="min-w-0">
            <header class="border-b border-[#dde6f1] bg-white px-5 py-5 sm:px-8">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#ff303f]">{{ now()->format('l j F') }}</p>
                        <h1 class="mt-1 text-3xl font-bold tracking-tight text-[#07182d]">@yield('heading')</h1>
                        @hasSection('subheading')
                            <p class="mt-1 text-sm text-slate-500">@yield('subheading')</p>
                        @endif
                    </div>
                    <a href="{{ route('book') }}" class="rounded-full bg-[#07182d] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0f2744]">New booking page</a>
                </div>
            </header>

            <main class="px-5 py-6 sm:px-8">
                @if (session('status'))
                    <p class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('status') }}</p>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

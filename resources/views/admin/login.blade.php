<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Office login · {{ config('school.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-[#07182d] px-4 font-sans text-slate-900" style="font-family:'Space Grotesk',ui-sans-serif,system-ui">
    <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="bg-[#ff303f] px-8 py-6 text-white">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-white/80">Staff office</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight">Sign in</h1>
            <p class="mt-1 text-sm text-white/85">Bookings and enquiries for King's Cross Driving School.</p>
        </div>
        <form class="space-y-4 px-8 py-8" method="post" action="{{ route('admin.login') }}">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="email">Email</label>
                <input id="email" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-[#ff303f]" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
                @error('email')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold" for="password">Password</label>
                <input id="password" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 outline-none focus:border-[#ff303f]" type="password" name="password" autocomplete="current-password" required>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1">
                Keep me signed in
            </label>
            <button class="w-full rounded-xl bg-[#07182d] px-4 py-3 text-sm font-bold text-white hover:bg-[#0f2744]" type="submit">Open office</button>
        </form>
    </div>
</body>
</html>

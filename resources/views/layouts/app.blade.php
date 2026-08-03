<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Preshop') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|space-grotesk:500,600,700|archivo:500,600,700,800,900|jetbrains-mono:400,500,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-paper font-sans text-ink selection:bg-accent selection:text-white">
    <a href="#content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-ink focus:px-4 focus:py-3 focus:text-white">Skip to content</a>
    <header class="border-b border-ink/10 bg-paper/90 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-10" aria-label="Workspace navigation">
            <div class="flex items-center gap-6"><a href="{{ route('home') }}" class="font-display text-xl font-bold tracking-tight">pre<span class="text-accent">.</span>shop</a><div class="hidden items-center gap-5 text-sm font-semibold md:flex"><a href="{{ route('customer.shop') }}" class="text-ink/65 hover:text-accent">Browse</a><a href="{{ route('dashboard') }}" class="text-ink/65 hover:text-accent">Workspace</a>@if(auth()->user()->is_admin)<a href="{{ route('admin.overview') }}" class="text-ink/65 hover:text-accent">Admin</a>@endif</div></div>
            <div class="flex items-center gap-3 text-sm"><a href="{{ route('profile.show') }}" class="hidden text-ink/65 hover:text-accent sm:inline">{{ auth()->user()->name }}</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rounded-full border border-ink/20 px-4 py-2 font-semibold hover:border-accent hover:text-accent">Log out</button></form></div>
        </nav>
    </header>
    @if (session('message'))<div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-10"><div class="rounded-xl bg-moss px-4 py-3 text-sm font-semibold text-white">{{ session('message') }}</div></div>@endif
    <main id="content">{{ $slot }}</main>
    <footer class="border-t border-ink/10 px-4 py-8 text-sm text-ink/55 sm:px-6 lg:px-10"><div class="mx-auto max-w-7xl">pre.shop · make something people can get behind.</div></footer>
    @stack('modals')
    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Preshop is a community preorder marketplace for ideas worth backing.">
    <title>{{ config('app.name', 'Preshop') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|space-grotesk:500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-paper font-sans text-ink selection:bg-accent selection:text-white">
    <a href="#content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-ink focus:px-4 focus:py-3 focus:text-white">Skip to content</a>
    <header class="border-b border-ink/10 bg-paper/90 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-10" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="font-display text-xl font-bold tracking-tight">pre<span class="text-accent">.</span>shop</a>
            <div class="flex items-center gap-3 text-sm font-semibold sm:gap-6">
                <a href="{{ route('customer.shop') }}" class="text-ink/65 hover:text-accent">Browse</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-ink px-4 py-2 text-paper hover:bg-accent">Workspace</a>
                @else
                    <a href="{{ route('login') }}" class="text-ink/65 hover:text-accent">Log in</a>
                    <a href="{{ route('register') }}" class="hidden rounded-full border border-ink px-4 py-2 hover:bg-ink hover:text-paper sm:inline-flex">Join the community</a>
                @endauth
            </div>
        </nav>
    </header>
    <main id="content">{{ $slot }}</main>
    <footer class="border-t border-ink/10 px-4 py-8 text-sm text-ink/55 sm:px-6 lg:px-10">
        <div class="mx-auto flex max-w-7xl flex-col justify-between gap-3 sm:flex-row">
            <span>pre.shop · ideas with a waiting list</span>
            <span>Demo marketplace</span>
        </div>
    </footer>
    @livewireScripts
</body>
</html>

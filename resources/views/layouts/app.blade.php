<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Preorder by HopeXito') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- File Pond --}}
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased selection:bg-indigo-600 selection:text-white">
    <x-banner />
    <div class="flex min-h-screen p-2 space-x-1 bg-neutral-800">
        @livewire('navigation-menu')
        <div class="w-full max-h-screen space-y-2 overflow-scroll">
            @if (isset($header))
                <header class="px-4 py-6 mx-auto border border-indigo-500 rounded-md bg-zinc-900 sm:px-6 lg:px-8">
                    <h1
                        class="flex items-center gap-2 text-xl font-medium leading-tight tracking-wider text-gray-200 uppercase">
                        {{ $header }}
                    </h1>
                </header>
            @endif
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('modals')
    @livewireScripts
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
</body>

</html>

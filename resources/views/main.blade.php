<x-guest-layout>
    <div
        class="relative min-h-screen bg-center bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-indigo-950 via-zinc-900 to-black sm:flex sm:justify-center sm:items-center">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <canvas data-particle-animation data-particle-quantity="50" data-particle-staticity="40"
                data-particle-ease="60"></canvas>
        </div>
        @if (Route::has('login'))
            <div class="z-10 flex items-center gap-4 p-6 text-right sm:fixed sm:top-0 sm:right-0">
                @auth
                    <a href="{{ url('/dashboard') }}" wire:navigate
                        class="font-semibold text-gray-400 hover:text-sky-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="font-semibold text-gray-400 hover:text-sky-600">Log
                        in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate
                            class="mx-4 font-semibold text-gray-400 hover:text-sky-600">Register</a>
                    @endif
                @endauth
                <a class="relative inline-block text-sm font-medium text-white transition group focus:outline-none focus:scale-95"
                    href="{{ route('login') }}" wire:navigate>
                    <span class="absolute inset-0 border border-sky-600 group-active:border-sky-500"></span>
                    <span
                        class="block px-12 py-3 transition-transform border bg-gradient-to-r from-sky-600 to-indigo-600 border-sky-600 active:border-sky-500 active:bg-sky-500 group-hover:-translate-x-1 group-hover:-translate-y-1">
                        Start a Campaign
                    </span>
                </a>
            </div>
        @endif
        <div class="flex flex-col items-center">
            <div class="flex items-center">
                <img src="{{ asset('asset/preorder.png') }}" alt="" class="w-16 -mr-2" />
                <h1
                    class="p-1 font-extrabold text-transparent text-7xl bg-clip-text bg-gradient-to-r from-sky-400 via-indigo-500 to-purple-400">
                    reorder by HopeXito
                </h1>
            </div>
            <p class="mt-4 text-lg text-gray-200">We know how hard it is to manage preorder campaigns manually. It
                doesn't
                have to be.</p>
            <p class="text-lg text-gray-200">Preorder made easy, <span class="font-semibold text-indigo-400">automate
                    operations</span> for <span class="font-semibold text-indigo-400">seamless management.</span></p>
            <div class="flex items-center gap-8 mt-6">
                <div class="relative group">
                    <div
                        class="absolute transition duration-1000 rounded-full opacity-25 -inset-1 bg-gradient-to-r from-sky-600 via-indigo-600 to-purple-600 blur group-hover:opacity-100 group-hover:duration-200">
                    </div>
                    <div
                        class="relative px-6 py-4 space-x-6 leading-none text-gray-200 bg-black rounded-full cursor-pointer">
                        <a href="{{ route('register') }}" wire:navigate>Get started - <span
                                class="tracking-wide text-blue-400">It's
                                free</span></a>
                    </div>
                </div>
                <button class="flex items-center gap-1 text-gray-200 group">
                    Learn more
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-5 transition-all group-hover:translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div
        class="relative min-h-screen bg-center bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-950 via-zinc-900 to-black sm:flex sm:justify-center sm:items-center">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <canvas data-particle-animation data-particle-quantity="50" data-particle-staticity="40"
                data-particle-ease="60"></canvas>
        </div>
        <div class="text-gray-200 max-w-7xl">
            <div class="grid grid-cols-2 grid-rows-5 gap-4">
                <div class="row-span-3 p-6 rounded-lg bg-white/10 backdrop-filter backdrop-blur-md">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8 p-1 bg-black rounded-md">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                        <p class="text-gray-400">
                            Publish
                        </p>
                    </div>
                    <p class="text-lg">It only takes 3 minutes</p>
                </div>
                <div class="row-span-2 p-6 rounded-lg bg-white/10 backdrop-filter backdrop-blur-md">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8 p-1 bg-black rounded-md">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <p class="text-gray-400">
                            Sales Tracker
                        </p>
                    </div>
                    <p class="text-lg">Export all sales data at the end of campaign</p>


                </div>
                <div class="row-span-3 p-6 space-y-2 rounded-lg bg-white/10 backdrop-filter backdrop-blur-md">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8 p-1 bg-black rounded-md">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                        </svg>
                        <p class="text-gray-400">
                            Export Data
                        </p>
                    </div>
                    <p class="text-lg">Export all sales data at the end of campaign</p>
                </div>
                <div class="row-span-2 p-6 rounded-lg bg-white/10 backdrop-filter backdrop-blur-md">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8 p-1 bg-black rounded-md">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                        </svg>
                        <p class="text-gray-400">
                            Advanced analytics
                        </p>
                    </div>
                    <p class="text-lg">Export all sales data at the end of campaign</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

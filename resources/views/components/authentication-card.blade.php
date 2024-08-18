<div
    class="flex flex-col items-center min-h-screen justify-center sm:pt-0 lg:bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-orange-400/10 via-blue-500/10 to-purple-600/10">
    <div class="text-center">
        {{ $logo }}
    </div>
    <div
        class="w-full px-6 py-4 mt-6 overflow-hidden shadow-lg bg-white/20 backdrop-filter backdrop-blur-md sm:max-w-md sm:rounded-lg">
        {{ $slot }}
    </div>
</div>

<div {{ $attributes->merge(['class' => ($attributes->has('fullHeight') ? 'min-h-screen ' : '') . 'mb-6 p-1.5']) }}>
    <div class="relative rounded-[2rem] bg-white shadow-[0_4px_60px_-2px_rgba(0,0,0,0.03)] ring-1 ring-black/[0.04] transition-elastic hover:translate-y-[-2px]">
        <h2 class="px-8 py-5 text-xs font-bold uppercase tracking-[0.25em] text-ink/30">{{ $title }}</h2>
        <div class="px-2 pb-6">
            {{ $slot }}
        </div>
    </div>
</div>

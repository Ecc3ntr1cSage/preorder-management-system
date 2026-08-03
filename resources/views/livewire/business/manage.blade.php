@php
    $activeCount = $campaigns->filter(fn ($campaign) => $campaign->end_date->isFuture())->count();
    $totalViews = $campaigns->sum('visitors_count');
@endphp

<x-slot name="header">
    <div class="flex items-center gap-3"><span class="flex size-8 items-center justify-center rounded-full bg-accent/10 text-accent"><svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true"><path d="M5 5h14v14H5zM8 9h8M8 12h8M8 15h5" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" /></svg></span><span class="text-sm font-semibold">Campaign atelier</span></div>
</x-slot>

<section class="relative isolate overflow-hidden bg-paper">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[34rem] bg-[radial-gradient(circle_at_12%_2%,rgba(105,115,91,.15),transparent_34%),radial-gradient(circle_at_92%_0%,rgba(232,111,81,.13),transparent_30%)]"></div>

    <div class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 sm:pt-14 lg:px-10 lg:pb-28">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end" data-animate="fade-up">
            <div class="max-w-3xl">
                <p class="inline-flex rounded-full bg-accent/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.22em] text-accent">Your campaign shelf</p>
                <h1 class="mt-5 font-display text-5xl font-semibold leading-[.92] tracking-[-0.07em] text-ink sm:text-7xl">Ideas in motion.</h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-ink/60">Keep every launch close: the live ones, the finished ones, and the next idea waiting for its first signal.</p>
            </div>
            <a href="{{ route('business.publish') }}" wire:navigate class="group inline-flex w-fit items-center gap-3 rounded-full bg-ink px-4 py-2.5 text-sm font-semibold text-paper shadow-[0_18px_40px_rgba(5,5,5,.12)] transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:bg-accent active:scale-[.98]"><span>New campaign</span><span class="flex size-7 items-center justify-center rounded-full bg-paper/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1"><svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true"><path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" /></svg></span></a>
        </div>

        <div class="mt-12 grid gap-4 sm:grid-cols-3" data-animate="fade-up" data-delay="100">
            <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="flex min-h-36 flex-col justify-between rounded-[calc(2rem-0.375rem)] bg-[#171412] p-6 text-paper shadow-[0_22px_65px_rgba(46,26,71,.12)]"><p class="text-[10px] uppercase tracking-[0.22em] text-paper/45">Total campaigns</p><p class="font-display text-4xl font-semibold tracking-[-0.06em]">{{ $campaigns->count() }}</p></div></div>
            <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="flex min-h-36 flex-col justify-between rounded-[calc(2rem-0.375rem)] bg-white/70 p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)]"><p class="text-[10px] uppercase tracking-[0.22em] text-moss">Currently live</p><p class="font-display text-4xl font-semibold tracking-[-0.06em]">{{ $activeCount }}</p></div></div>
            <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="flex min-h-36 flex-col justify-between rounded-[calc(2rem-0.375rem)] bg-moss p-6 text-paper shadow-[0_20px_60px_rgba(105,115,91,.15)]"><p class="text-[10px] uppercase tracking-[0.22em] text-paper/55">All-time views</p><p class="font-display text-4xl font-semibold tracking-[-0.06em]">{{ number_format($totalViews) }}</p></div></div>
        </div>

        <div class="mt-16 flex items-end justify-between gap-5 border-b border-ink/10 pb-4" data-animate="fade-up" data-delay="160"><div><p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">Archive</p><h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.05em]">Every campaign</h2></div><span class="hidden text-xs text-ink/45 sm:block">{{ $campaigns->count() }} {{ $campaigns->count() === 1 ? 'edition' : 'editions' }}</span></div>

        <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($campaigns as $campaign)
                @php
                    $cover = $campaign->images->first();
                    $isActive = $campaign->end_date->isFuture();
                    $daysLeft = $isActive ? max(1, now()->diffInDays($campaign->end_date)) : 0;
                @endphp
                <a href="{{ route('business.info', $campaign->slug) }}" wire:navigate class="group rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-1 {{ $loop->first ? 'lg:col-span-2' : '' }}" data-animate="fade-up" data-delay="{{ min($loop->index * 80, 320) }}">
                    <div class="h-full overflow-hidden rounded-[calc(2rem-0.375rem)] bg-white/75 shadow-[0_20px_60px_rgba(71,52,38,.07)]">
                        <div class="relative overflow-hidden bg-moss/10 {{ $loop->first ? 'aspect-[16/8]' : 'aspect-[4/3]' }}">
                            <img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/product/field-notes.png') }}" alt="{{ $campaign->title }} campaign" class="h-full w-full object-cover opacity-90 transition-transform duration-[1200ms] ease-[cubic-bezier(.32,.72,0,1)] group-hover:scale-105" />
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>
                            <div class="absolute left-4 top-4 flex items-center gap-2"><span class="rounded-full bg-paper/90 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.17em] text-ink">{{ $isActive ? 'Live' : 'Closed' }}</span>@if ($isActive)<span class="rounded-full bg-ink/65 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.17em] text-paper">{{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }} left</span>@endif</div>
                            <span class="absolute bottom-4 right-4 flex size-9 items-center justify-center rounded-full bg-paper/90 text-ink transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-1"><svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true"><path d="M5 12h13m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                        </div>
                        <div class="p-5 sm:p-6"><div class="flex items-start justify-between gap-4"><div><p class="text-[10px] font-semibold uppercase tracking-[0.17em] text-moss">{{ $campaign->start_date->format('d M Y') }} — {{ $campaign->end_date->format('d M Y') }}</p><h3 class="mt-3 font-display text-2xl font-semibold leading-tight tracking-[-0.045em] text-ink">{{ $campaign->title }}</h3></div><span class="shrink-0 font-display text-lg font-semibold text-accent">{{ $campaign->currency }}{{ number_format($campaign->price / 100, 2) }}</span></div><p class="mt-3 line-clamp-2 text-sm leading-6 text-ink/55">{{ $campaign->description }}</p><div class="mt-6 flex items-center justify-between gap-4 border-t border-ink/10 pt-4 text-xs text-ink/45"><span>{{ $campaign->visitors_count }} views</span><span>Open studio <span class="text-accent">↗</span></span></div></div>
                    </div>
                </a>
            @empty
                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10 md:col-span-2 lg:col-span-3"><div class="rounded-[calc(2rem-0.375rem)] bg-[#171412] p-10 text-center text-paper sm:p-16"><p class="text-[10px] uppercase tracking-[0.22em] text-paper/45">Blank shelf</p><h3 class="mt-4 font-display text-3xl font-semibold tracking-[-0.04em]">Your first campaign starts here.</h3><p class="mx-auto mt-3 max-w-sm text-sm leading-6 text-paper/55">Give one clear idea a place to gather its people.</p><a href="{{ route('business.publish') }}" wire:navigate class="group mt-7 inline-flex items-center gap-3 rounded-full bg-gold px-4 py-2.5 text-sm font-semibold text-ink transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 active:scale-[.98]">Create campaign <span class="flex size-7 items-center justify-center rounded-full bg-ink/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1"><svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true"><path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" /></svg></span></a></div></div>
            @endforelse
        </div>
    </div>
    <x-flash />
</section>

@inject('carbon', 'Carbon\\Carbon')
@php
    $statusLabels = ['Preorder', 'Campaign ended', 'Shipped', 'Delivered'];
    $backedTotal = $orders->sum('amount');
@endphp

<section class="relative isolate overflow-hidden bg-paper">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[30rem] bg-[radial-gradient(circle_at_15%_5%,rgba(105,115,91,.16),transparent_35%),radial-gradient(circle_at_90%_12%,rgba(232,111,81,.13),transparent_32%)]"></div>

    <div class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 sm:pt-16 lg:px-10 lg:pb-28">
        <div class="max-w-3xl">
            <p class="inline-flex rounded-full bg-accent/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-accent">Your backing ledger</p>
            <h1 class="mt-5 font-display text-5xl font-semibold leading-[.95] tracking-[-0.06em] text-ink sm:text-7xl">Past orders,<br><span class="text-moss">kept in one place.</span></h1>
            <p class="mt-5 max-w-xl text-base leading-7 text-ink/60">A quiet record of the ideas you chose to move forward. Revisit any receipt for the latest order progress.</p>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-[1.35fr_.65fr_.65fr]">
            <div class="rounded-[2rem] bg-ink p-1.5 ring-1 ring-ink/10">
                <div class="flex h-full min-h-40 flex-col justify-between rounded-[calc(2rem-0.375rem)] bg-[#171412] p-6 text-paper sm:p-8">
                    <p class="text-[10px] uppercase tracking-[0.22em] text-paper/40">The running total</p>
                    <div class="mt-10 flex items-end justify-between gap-4"><p class="font-display text-4xl font-semibold tracking-[-0.05em] sm:text-5xl">RM{{ number_format($backedTotal / 100, 2) }}</p><span class="mb-1 text-sm text-gold">backed</span></div>
                </div>
            </div>
            <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="flex h-full min-h-40 flex-col justify-between rounded-[calc(2rem-0.375rem)] bg-white/70 p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)]"><p class="text-[10px] uppercase tracking-[0.22em] text-moss">Orders</p><p class="font-display text-4xl font-semibold tracking-[-0.05em]">{{ $orders->count() }}</p></div></div>
            <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="flex h-full min-h-40 flex-col justify-between rounded-[calc(2rem-0.375rem)] bg-white/70 p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)]"><p class="text-[10px] uppercase tracking-[0.22em] text-moss">Member since</p><p class="font-display text-2xl font-semibold tracking-[-0.04em]">{{ optional($orders->last()?->paid_at)->format('Y') ?? '—' }}</p></div></div>
        </div>

        <div class="mt-16 flex items-end justify-between gap-6 border-b border-ink/10 pb-4">
            <div><p class="text-[10px] uppercase tracking-[0.22em] text-moss">Archive</p><h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.04em]">Every order</h2></div>
            <span class="hidden text-xs text-ink/45 sm:block">{{ $orders->count() }} {{ $orders->count() === 1 ? 'record' : 'records' }}</span>
        </div>

        <div class="mt-5 space-y-4">
            @forelse ($orders as $order)
                @php
                    $statusIndex = min(max((int) $order->status, 0), 3);
                    $cover = $order->campaign?->images?->first();
                @endphp
                <article wire:key="{{ $order->id }}" wire:loading.class="opacity-60" class="group rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5">
                    <div class="grid gap-5 rounded-[calc(2rem-0.375rem)] bg-white/75 p-4 shadow-[0_18px_55px_rgba(71,52,38,.06)] sm:grid-cols-[7rem_1fr_auto] sm:items-center sm:gap-7 sm:p-5 lg:grid-cols-[8rem_1fr_11rem_auto]">
                        <div class="aspect-square overflow-hidden rounded-[1.25rem] bg-moss/10"><img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/product/field-notes.png') }}" alt="{{ $order->campaign?->title ?? 'Backed campaign' }}" class="h-full w-full object-cover transition-transform duration-[1000ms] ease-[cubic-bezier(.32,.72,0,1)] group-hover:scale-105" /></div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-moss"><span>PS-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span><span class="size-1 rounded-full bg-ink/20"></span><span>{{ optional($order->paid_at)->format('d M Y') ?? 'Date unavailable' }}</span></div>
                            <h3 class="mt-3 truncate font-display text-2xl font-semibold tracking-[-0.04em] text-ink">{{ $order->campaign?->title ?? 'Untitled campaign' }}</h3>
                            <p class="mt-2 text-sm text-ink/55">{{ $order->variations ? ucfirst($order->variations) . ' · ' : '' }}{{ $order->quantity }} {{ $order->quantity === 1 ? 'unit' : 'units' }} · {{ $order->address }}, {{ $order->state }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-4 sm:block sm:text-right"><div><p class="text-[10px] uppercase tracking-[0.18em] text-ink/40">Status</p><p class="mt-2 text-sm font-semibold text-moss">{{ $statusLabels[$statusIndex] }}</p></div><p class="mt-3 font-display text-xl font-semibold text-accent">RM{{ number_format($order->amount / 100, 2) }}</p></div>
                        <a href="{{ route('customer.invoice', $order) }}" wire:navigate aria-label="View order PS-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}" class="group/link inline-flex w-full items-center justify-between gap-3 rounded-full bg-ink px-4 py-2.5 text-sm font-semibold text-paper transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:bg-accent active:scale-[.98] sm:w-auto">
                            <span>View order</span><span class="flex size-7 items-center justify-center rounded-full bg-paper/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover/link:translate-x-1"><svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true"><path d="M5 12h13m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="rounded-[calc(2rem-0.375rem)] bg-[#171412] p-10 text-center text-paper sm:p-16"><p class="text-[10px] uppercase tracking-[0.22em] text-paper/45">Nothing here yet</p><h3 class="mt-4 font-display text-3xl font-semibold tracking-[-0.04em]">Your first backing is waiting.</h3><p class="mx-auto mt-3 max-w-sm text-sm leading-6 text-paper/55">Explore the shop and find an idea worth getting behind.</p><a href="{{ route('customer.shop') }}" wire:navigate class="mt-7 inline-flex items-center gap-3 rounded-full bg-gold px-4 py-2.5 text-sm font-semibold text-ink transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 active:scale-[.98]">Browse campaigns <span class="flex size-7 items-center justify-center rounded-full bg-ink/10">↗</span></a></div></div>
            @endforelse
        </div>
    </div>
</section>

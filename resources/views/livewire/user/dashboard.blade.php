    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-10">
        <section class="relative overflow-hidden rounded-[2rem] bg-ink px-6 py-10 text-paper sm:px-10">
            <div class="grain pointer-events-none absolute inset-0 opacity-30"></div>
            <div class="relative flex flex-col justify-between gap-8 md:flex-row md:items-end">
                <div class="max-w-2xl">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.28em] text-accent">Your workspace</p>
                    <h1 class="font-display text-4xl font-semibold tracking-tight sm:text-6xl">Make something people can get behind.</h1>
                    <p class="mt-4 max-w-xl text-base leading-7 text-paper/70">Launch a preorder, gather your community, and keep every order in one place.</p>
                </div>
                <a href="{{ route('business.publish') }}" class="inline-flex shrink-0 items-center justify-center rounded-full bg-accent px-5 py-3 text-sm font-bold text-white hover:-translate-y-0.5 hover:bg-accent-dark">Start a campaign <span class="ml-2">↗</span></a>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-ink/55">Campaigns</p><p class="mt-2 font-display text-3xl font-semibold">{{ $campaignCount }}</p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-ink/55">Paid orders</p><p class="mt-2 font-display text-3xl font-semibold">{{ $salesCount }}</p></div>
            <div class="rounded-2xl bg-moss p-5 text-paper shadow-sm"><p class="text-sm text-paper/65">Available balance</p><p class="mt-2 font-display text-3xl font-semibold">RM {{ number_format(($wallet?->balance ?? 0) / 100, 2) }}</p></div>
        </section>

        <div class="grid gap-8 lg:grid-cols-2">
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-[0.2em] text-moss">Your work</p><h2 class="mt-1 font-display text-2xl font-semibold">Recent campaigns</h2></div><a href="{{ route('business.manage') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">View all</a></div>
                <div class="mt-6 divide-y divide-ink/10">
                    @forelse ($campaigns as $campaign)
                        <a href="{{ route('business.info', $campaign) }}" class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0 hover:text-accent"><div><p class="font-semibold">{{ $campaign->title }}</p><p class="mt-1 text-sm text-ink/55">{{ $campaign->orders_count ?? $campaign->orders()->count() }} orders · {{ ucfirst($campaign->status == 1 ? 'live' : 'ended') }}</p></div><span class="text-xl">↗</span></a>
                    @empty
                        <div class="rounded-xl bg-paper p-5 text-sm text-ink/60">You have no campaigns yet. Start with one clear idea.</div>
                    @endforelse
                </div>
            </section>

            <section x-data="{ tab: 'orders' }" class="rounded-2xl bg-ink p-6 text-paper shadow-sm">
                <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-[0.2em] text-accent">Your activity</p><h2 class="mt-1 font-display text-2xl font-semibold">Orders &amp; sales</h2></div></div>
                <div class="mt-6 flex rounded-full bg-paper/5 p-1 ring-1 ring-paper/10">
                    <button type="button" x-on:click="tab = 'orders'" x-bind:class="tab === 'orders' ? 'bg-gold text-ink' : 'text-paper/55 hover:text-paper'" class="flex-1 rounded-full px-3 py-2 text-xs font-semibold transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)]">Your orders</button>
                    <button type="button" x-on:click="tab = 'sales'" x-bind:class="tab === 'sales' ? 'bg-gold text-ink' : 'text-paper/55 hover:text-paper'" class="flex-1 rounded-full px-3 py-2 text-xs font-semibold transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)]">Your sales</button>
                </div>
                <div x-cloak x-show="tab === 'orders'" x-transition:enter="transition duration-500 ease-[cubic-bezier(.32,.72,0,1)]" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-6 space-y-4">
                    <div class="flex items-center justify-between gap-4"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-paper/45">Recent orders</p><a href="{{ route('customer.history') }}" wire:navigate class="group inline-flex items-center gap-2 text-xs font-semibold text-paper/60 transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:text-accent">View all <span class="transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1">↗</span></a></div>
                    @forelse ($orders as $order)
                        <a href="{{ route('customer.invoice', $order) }}" class="block border-b border-paper/15 pb-4 last:border-0 last:pb-0 hover:text-accent"><div class="flex justify-between gap-4"><span class="font-semibold">{{ $order->campaign?->title ?? 'Campaign' }}</span><span>RM {{ number_format($order->amount / 100, 2) }}</span></div><p class="mt-1 text-sm text-paper/55">{{ $order->paid ? 'Paid' : 'Pending' }} · {{ optional($order->paid_at)->format('d M Y') }}</p></a>
                    @empty
                        <p class="text-sm leading-6 text-paper/60">Your order history will appear here after your first preorder.</p>
                    @endforelse
                </div>
                <div x-cloak x-show="tab === 'sales'" x-transition:enter="transition duration-500 ease-[cubic-bezier(.32,.72,0,1)]" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-6 space-y-4">
                    <div class="flex items-center justify-between gap-4"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-paper/45">Recent sales</p><a href="{{ route('business.sales') }}" wire:navigate class="group inline-flex items-center gap-2 text-xs font-semibold text-paper/60 transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:text-accent">View all <span class="transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1">↗</span></a></div>
                    @forelse ($sales as $sale)
                        <div class="border-b border-paper/15 pb-4 last:border-0 last:pb-0"><div class="flex justify-between gap-4"><span class="font-semibold">{{ $sale->campaign?->title ?? 'Campaign' }}</span><span>RM {{ number_format($sale->amount / 100, 2) }}</span></div><p class="mt-1 text-sm text-paper/55">{{ $sale->name }} · {{ $sale->paid ? 'Paid' : 'Pending' }} · {{ optional($sale->paid_at)->format('d M Y') }}</p></div>
                    @empty
                        <p class="text-sm leading-6 text-paper/60">Sales from your campaigns will appear here.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

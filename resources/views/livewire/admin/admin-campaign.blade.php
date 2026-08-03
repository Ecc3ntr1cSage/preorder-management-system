<div class="min-h-[100dvh] bg-paper">
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[0.28em] text-moss">Marketplace review</p><h1 class="mt-3 font-display text-4xl font-bold tracking-[-0.04em]">Campaigns</h1><p class="mt-2 text-sm text-ink/55">Inspect what makers are putting in front of the community.</p></div>
            <div class="grid gap-2 sm:grid-cols-[1fr_auto_auto]">
                <label><span class="sr-only">Search campaigns</span><input wire:model.live.debounce.300ms="search" type="search" placeholder="Search title or maker" class="w-full rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"></label>
                <label><span class="sr-only">Campaign status</span><select wire:model.live="statusFilter" class="rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"><option value="">All statuses</option><option value="1">Live</option><option value="2">Ended</option></select></label>
                <select wire:model.live="perPage" aria-label="Rows per page" class="rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"><option value="12">12 rows</option><option value="24">24 rows</option><option value="48">48 rows</option></select>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] bg-white shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5">
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full text-left text-sm">
                    <thead class="border-b border-ink/10 bg-ink/[.03] text-[10px] uppercase tracking-[0.18em] text-ink/45"><tr><th class="px-6 py-4"><button wire:click="sort('title','asc')" class="hover:text-accent">Campaign ↑</button></th><th class="px-6 py-4">Maker</th><th class="px-6 py-4"><button wire:click="sort('price','asc')" class="hover:text-accent">Price ↑</button></th><th class="px-6 py-4">Orders</th><th class="px-6 py-4"><button wire:click="sort('end_date','asc')" class="hover:text-accent">Ends ↑</button></th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Open</th></tr></thead>
                    <tbody class="divide-y divide-ink/10">
                        @forelse ($campaigns as $campaign)
                            @php($isLive = $campaign->status === 1)
                            <tr wire:key="campaign-{{ $campaign->id }}" wire:loading.class="opacity-50" class="transition hover:bg-paper/60"><td class="px-6 py-5"><p class="font-semibold">{{ $campaign->title }}</p><p class="mt-1 font-mono text-[11px] text-ink/40">PS-{{ str_pad($campaign->id, 4, '0', STR_PAD_LEFT) }}</p></td><td class="px-6 py-5 text-ink/60">{{ $campaign->user->name }}</td><td class="px-6 py-5 font-mono tabular-nums">RM {{ number_format($campaign->price / 100, 2) }}</td><td class="px-6 py-5 font-mono tabular-nums">{{ $campaign->orders_count }}</td><td class="px-6 py-5 whitespace-nowrap text-ink/55">{{ $campaign->end_date->format('d M Y') }}</td><td class="px-6 py-5"><span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $isLive ? 'bg-moss/10 text-moss' : 'bg-ink/5 text-ink/45' }}"><span class="size-1.5 rounded-full {{ $isLive ? 'bg-moss' : 'bg-ink/30' }}"></span>{{ $isLive ? 'Live' : 'Ended' }}</span></td><td class="px-6 py-5 text-right"><a href="{{ route('admin.campaign.info', $campaign) }}" wire:navigate class="font-semibold text-moss hover:text-accent">Review ↗</a></td></tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-14 text-center text-ink/50">No campaigns match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-ink/10 px-6 py-4">{{ $campaigns->links() }}</div>
        </div>
    </div>
</div>

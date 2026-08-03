<div class="min-h-[100dvh] bg-paper">
    <div class="mx-auto max-w-7xl space-y-10 px-4 py-10 sm:px-6 lg:px-10">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-moss">Operations ledger</p>
                <h1 class="mt-3 font-display text-4xl font-bold tracking-[-0.04em] text-ink sm:text-5xl">Market pulse</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-ink/55">A clear read on paid orders, live campaigns, and the payout queue.</p>
            </div>
            <div wire:loading class="text-xs font-semibold uppercase tracking-[0.18em] text-ink/40">Refreshing ledger…</div>
        </div>

        <section class="overflow-hidden rounded-[2rem] bg-ink text-paper shadow-[12px_14px_0_rgba(105,115,91,.22)]" aria-label="Market summary">
            <div class="grid divide-y divide-paper/15 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">
                <div class="p-6 sm:p-7"><p class="text-[10px] uppercase tracking-[0.2em] text-paper/45">Paid GMV</p><p class="mt-4 font-mono text-2xl font-bold tabular-nums">RM {{ number_format($totalSales / 100, 2) }}</p><p class="mt-2 text-xs text-paper/45">{{ $paidOrderCount }} paid orders</p></div>
                <div class="p-6 sm:p-7"><p class="text-[10px] uppercase tracking-[0.2em] text-paper/45">Platform fees</p><p class="mt-4 font-mono text-2xl font-bold tabular-nums text-accent">RM {{ number_format($totalFees / 100, 2) }}</p><p class="mt-2 text-xs text-paper/45">Collected from paid orders</p></div>
                <div class="p-6 sm:p-7"><p class="text-[10px] uppercase tracking-[0.2em] text-paper/45">Live campaigns</p><p class="mt-4 font-mono text-2xl font-bold tabular-nums text-gold">{{ $activeCampaignCount }}</p><p class="mt-2 text-xs text-paper/45">{{ $endedCampaignCount }} ended</p></div>
                <div class="bg-accent/10 p-6 sm:p-7"><p class="text-[10px] uppercase tracking-[0.2em] text-paper/55">Payout queue</p><p class="mt-4 font-mono text-2xl font-bold tabular-nums">RM {{ number_format($pendingWithdrawalAmount / 100, 2) }}</p><p class="mt-2 text-xs text-paper/55">{{ $pendingWithdrawalCount }} waiting for review</p></div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.15fr_.85fr]">
            <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div><p class="text-[10px] font-bold uppercase tracking-[0.22em] text-accent">Needs attention</p><h2 class="mt-2 font-display text-2xl font-bold">Pending payouts</h2></div>
                    <a href="{{ route('admin.wallet') }}" wire:navigate class="text-sm font-semibold text-moss hover:text-accent">Open queue ↗</a>
                </div>
                <div class="mt-6 divide-y divide-ink/10">
                    @forelse ($pendingWithdrawals as $withdrawal)
                        <div class="flex flex-col gap-3 py-4 first:pt-0 sm:flex-row sm:items-center sm:justify-between" wire:key="pending-{{ $withdrawal->id }}">
                            <div><p class="font-semibold">{{ $withdrawal->wallet->user->name }}</p><p class="mt-1 text-xs text-ink/50">{{ $withdrawal->wallet->bank_name }} · account ending {{ substr($withdrawal->wallet->bank_account_number, -4) }}</p></div>
                            <div class="sm:text-right"><p class="font-mono font-bold tabular-nums">RM {{ number_format($withdrawal->withdrawn_amount / 100, 2) }}</p><p class="mt-1 text-xs text-ink/45">{{ $withdrawal->created_at->format('d M Y, g:i A') }}</p></div>
                        </div>
                    @empty
                        <p class="py-6 text-sm text-ink/50">The payout queue is clear.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5 sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-moss">Order health</p>
                <h2 class="mt-2 font-display text-2xl font-bold">Paid order flow</h2>
                @php($statusLabels = [0 => 'Pending', 1 => 'Paid', 2 => 'Shipped', 3 => 'Delivered'])
                <div class="mt-6 space-y-4">
                    @foreach ($statusLabels as $status => $label)
                        @php($count = $orderStatusCounts[$status] ?? 0)
                        @php($percentage = $paidOrderCount > 0 ? ($count / $paidOrderCount) * 100 : 0)
                        <div>
                            <div class="flex justify-between gap-4 text-sm"><span class="text-ink/55">{{ $label }}</span><span class="font-mono font-bold tabular-nums">{{ $count }} · {{ number_format($percentage, 0) }}%</span></div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-ink/5"><div class="h-full rounded-full bg-moss transition-all duration-700" style="width: {{ $percentage }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[.85fr_1.15fr]">
            <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5 sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-moss">Campaign health</p>
                <h2 class="mt-2 font-display text-2xl font-bold">At a glance</h2>
                <dl class="mt-7 space-y-4 text-sm">
                    <div class="flex justify-between border-t border-ink/10 pt-4"><dt class="text-ink/50">Average duration</dt><dd class="font-mono font-bold tabular-nums">{{ number_format($averageCampaignDuration ?? 0, 0) }} days</dd></div>
                    <div class="flex justify-between border-t border-ink/10 pt-4"><dt class="text-ink/50">Average price</dt><dd class="font-mono font-bold tabular-nums">RM {{ number_format(($averagePrice ?? 0) / 100, 2) }}</dd></div>
                    <div class="flex justify-between border-t border-ink/10 pt-4"><dt class="text-ink/50">Quantity backed</dt><dd class="font-mono font-bold tabular-nums">{{ number_format($totalQuantity) }}</dd></div>
                    <div class="flex justify-between border-t border-ink/10 pt-4"><dt class="text-ink/50">Shipping collected</dt><dd class="font-mono font-bold tabular-nums">RM {{ number_format($totalShipping / 100, 2) }}</dd></div>
                </dl>
            </div>

            <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5 sm:p-8">
                <div class="flex items-end justify-between gap-4"><div><p class="text-[10px] font-bold uppercase tracking-[0.22em] text-moss">Recent activity</p><h2 class="mt-2 font-display text-2xl font-bold">Latest paid orders</h2></div><a href="{{ route('admin.sale') }}" wire:navigate class="text-sm font-semibold text-moss hover:text-accent">View sales ↗</a></div>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-ink/10 text-[10px] uppercase tracking-[0.18em] text-ink/40"><tr><th class="pb-3 pr-4">Order</th><th class="pb-3 pr-4">Campaign</th><th class="pb-3 pr-4">Customer</th><th class="pb-3 text-right">Amount</th></tr></thead>
                        <tbody class="divide-y divide-ink/10">
                            @forelse ($recentOrders as $order)
                                <tr wire:key="recent-order-{{ $order->id }}"><td class="py-4 pr-4 font-mono text-xs font-bold">#{{ $order->id }}</td><td class="py-4 pr-4 font-semibold">{{ $order->campaign?->title ?? 'Campaign' }}</td><td class="py-4 pr-4 text-ink/55">{{ $order->name }}</td><td class="py-4 text-right font-mono font-bold tabular-nums">RM {{ number_format($order->amount / 100, 2) }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="py-8 text-center text-ink/50">No paid orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

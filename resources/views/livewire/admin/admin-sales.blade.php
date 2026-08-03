<div class="min-h-[100dvh] bg-paper">
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[0.28em] text-moss">Order ledger</p><h1 class="mt-3 font-display text-4xl font-bold tracking-[-0.04em]">Sales</h1><p class="mt-2 text-sm text-ink/55">Search paid and pending orders across every campaign.</p></div>
            <div class="grid gap-2 sm:grid-cols-[1fr_auto_auto_auto]">
                <label><span class="sr-only">Search orders</span><input wire:model.live.debounce.300ms="search" type="search" placeholder="Order, customer, email, campaign" class="w-full rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"></label>
                <label><span class="sr-only">Payment status</span><select wire:model.live="paymentFilter" class="rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"><option value="">All payments</option><option value="paid">Paid</option><option value="pending">Pending</option></select></label>
                <label><span class="sr-only">Order status</span><select wire:model.live="statusFilter" class="rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"><option value="">All fulfilment</option><option value="0">Pending</option><option value="1">Paid</option><option value="2">Shipped</option><option value="3">Delivered</option></select></label>
                <select wire:model.live="perPage" aria-label="Rows per page" class="rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"><option value="20">20 rows</option><option value="40">40 rows</option><option value="80">80 rows</option></select>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] bg-white shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5">
            <div class="overflow-x-auto">
                <table class="min-w-[1040px] w-full text-left text-sm">
                    <thead class="border-b border-ink/10 bg-ink/[.03] text-[10px] uppercase tracking-[0.18em] text-ink/45"><tr><th class="px-6 py-4">Order</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Campaign</th><th class="px-6 py-4">Items</th><th class="px-6 py-4"><button wire:click="sort('amount','desc')" class="hover:text-accent">Amount ↓</button></th><th class="px-6 py-4">Payment</th><th class="px-6 py-4">Fulfilment</th><th class="px-6 py-4"><button wire:click="sort('paid_at','desc')" class="hover:text-accent">Paid at ↓</button></th></tr></thead>
                    <tbody class="divide-y divide-ink/10">
                        @php($statusLabels = [0 => 'Pending', 1 => 'Paid', 2 => 'Shipped', 3 => 'Delivered'])
                        @forelse ($orders as $order)
                            <tr wire:key="order-{{ $order->id }}" wire:loading.class="opacity-50" class="transition hover:bg-paper/60"><td class="px-6 py-5 font-mono text-xs font-bold">#{{ $order->id }}</td><td class="px-6 py-5"><p class="font-semibold">{{ $order->name }}</p><p class="mt-1 text-xs text-ink/45">{{ $order->email }}</p></td><td class="px-6 py-5 max-w-56 text-ink/60">{{ $order->campaign?->title ?? 'Campaign' }}</td><td class="px-6 py-5 font-mono tabular-nums">{{ $order->quantity }}</td><td class="px-6 py-5 font-mono font-bold tabular-nums">RM {{ number_format($order->amount / 100, 2) }}</td><td class="px-6 py-5"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $order->paid ? 'bg-moss/10 text-moss' : 'bg-accent/10 text-accent-dark' }}">{{ $order->paid ? 'Paid' : 'Pending' }}</span></td><td class="px-6 py-5 text-xs font-semibold text-ink/60">{{ $statusLabels[$order->status] ?? 'Unknown' }}</td><td class="px-6 py-5 whitespace-nowrap text-xs text-ink/50">{{ optional($order->paid_at)->format('d M Y, g:i A') ?? '—' }}</td></tr>
                        @empty
                            <tr><td colspan="8" class="px-6 py-14 text-center text-ink/50">No orders match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-ink/10 px-6 py-4">{{ $orders->links() }}</div>
        </div>
    </div>
</div>

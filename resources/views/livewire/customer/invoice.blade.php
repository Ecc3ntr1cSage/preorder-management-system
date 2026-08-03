@inject('carbon', 'Carbon\\Carbon')
@php
    $statusLabels = ['Preorder', 'Campaign ended', 'Shipped', 'Delivered'];
    $statusIndex = min(max((int) $order->status, 0), 3);
    $campaign = $order->campaign;
    $cover = $campaign?->images?->first();
    $subtotal = $order->amount + $order->discount - $order->shipping;
@endphp

<section class="relative isolate overflow-hidden bg-paper">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[32rem] bg-[radial-gradient(circle_at_8%_10%,rgba(105,115,91,.16),transparent_34%),radial-gradient(circle_at_92%_0%,rgba(232,111,81,.14),transparent_30%)]"></div>

    <div class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 sm:pt-16 lg:px-10 lg:pb-28">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <div class="max-w-2xl">
                <p class="inline-flex rounded-full bg-moss/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">Order record · PS-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                <h1 class="mt-5 max-w-xl font-display text-5xl font-semibold leading-[.95] tracking-[-0.06em] text-ink sm:text-7xl">A small promise, now on record.</h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-ink/60">Your preorder is safely logged. Keep this page close for the latest movement on your backed idea.</p>
            </div>
            <a href="{{ route('customer.history') }}" wire:navigate class="group inline-flex w-fit items-center gap-3 rounded-full bg-ink px-4 py-2.5 text-sm font-semibold text-paper shadow-[0_18px_40px_rgba(5,5,5,.12)] transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:bg-accent active:scale-[.98]">
                <span>Past orders</span>
                <span class="flex size-7 items-center justify-center rounded-full bg-paper/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1">
                    <svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true"><path d="M5 12h13m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </span>
            </a>
        </div>

        <div class="mt-12 grid gap-5 lg:grid-cols-[minmax(0,1.15fr)_minmax(21rem,.85fr)] lg:items-start">
            <article class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10" wire:loading.class="opacity-60">
                <div class="overflow-hidden rounded-[calc(2rem-0.375rem)] bg-[#171412] text-paper shadow-[0_28px_80px_rgba(46,26,71,.14)]">
                    <div class="grid gap-8 p-6 sm:p-9 lg:grid-cols-[minmax(10rem,.38fr)_1fr] lg:gap-10">
                        <div class="relative aspect-[4/5] overflow-hidden rounded-[1.4rem] bg-[#2b2420]">
                            <img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/product/field-notes.png') }}" alt="{{ $campaign?->title ?? 'Backed campaign' }}" class="h-full w-full object-cover opacity-90 transition-transform duration-[1200ms] ease-[cubic-bezier(.32,.72,0,1)] hover:scale-105" />
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            <span class="absolute bottom-4 left-4 rounded-full bg-paper/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-ink">Paid</span>
                        </div>

                        <div class="flex flex-col justify-between gap-10">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.22em] text-paper/45">Backed campaign</p>
                                <h2 class="mt-3 font-display text-3xl font-semibold leading-tight tracking-[-0.04em] sm:text-4xl">{{ $campaign?->title ?? 'Untitled campaign' }}</h2>
                                <p class="mt-3 max-w-md text-sm leading-6 text-paper/60">{{ $order->variations ? ucfirst($order->variations) : 'Standard selection' }} · {{ $order->quantity }} {{ $order->quantity === 1 ? 'unit' : 'units' }}</p>
                            </div>

                            <dl class="grid grid-cols-2 gap-6 border-t border-paper/10 pt-6 text-sm sm:grid-cols-3">
                                <div><dt class="text-[10px] uppercase tracking-[0.18em] text-paper/40">Placed</dt><dd class="mt-2 text-paper/85">{{ optional($order->paid_at)->format('d M Y') ?? '—' }}</dd></div>
                                <div><dt class="text-[10px] uppercase tracking-[0.18em] text-paper/40">Quantity</dt><dd class="mt-2 text-paper/85">{{ $order->quantity }}</dd></div>
                                <div><dt class="text-[10px] uppercase tracking-[0.18em] text-paper/40">Order total</dt><dd class="mt-2 font-semibold text-gold">RM{{ number_format($order->amount / 100, 2) }}</dd></div>
                            </dl>
                        </div>
                    </div>

                    <div class="mx-6 mb-6 rounded-[1.35rem] bg-paper/[.07] p-5 sm:mx-9 sm:mb-9 sm:p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div><p class="text-[10px] uppercase tracking-[0.2em] text-paper/40">Current stage</p><p class="mt-2 font-display text-xl font-medium">{{ $statusLabels[$statusIndex] }}</p></div>
                            <span class="flex size-10 items-center justify-center rounded-full bg-gold/15 text-gold">
                                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" /><circle cx="12" cy="12" r="8.25" stroke="currentColor" stroke-width="1.35" /></svg>
                            </span>
                        </div>
                        <div class="mt-7" aria-label="Order progress">
                            <div class="relative h-1 rounded-full bg-paper/10"><div class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-gold to-accent {{ $statusIndex === 0 ? 'w-[8%]' : ($statusIndex === 1 ? 'w-[38%]' : ($statusIndex === 2 ? 'w-[70%]' : 'w-full')) }}"></div></div>
                            <ol class="mt-4 grid grid-cols-4 gap-2 text-[10px] uppercase tracking-[0.12em] text-paper/45">
                                @foreach ($statusLabels as $index => $label)
                                    <li class="{{ $index <= $statusIndex ? 'text-paper/85' : '' }} {{ $index === 3 ? 'text-right' : ($index > 0 ? 'text-center' : '') }}"><span class="mb-2 inline-flex size-2 rounded-full {{ $index <= $statusIndex ? 'bg-gold' : 'bg-paper/20' }}"></span><span class="block">{{ $label }}</span></li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </article>

            <aside class="space-y-5">
                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                    <div class="rounded-[calc(2rem-0.375rem)] bg-white/70 p-6 shadow-[0_24px_70px_rgba(71,52,38,.09)] sm:p-8">
                        <div class="flex items-start justify-between gap-5">
                            <div><p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">Payment summary</p><h2 class="mt-3 font-display text-2xl font-semibold tracking-[-0.04em]">Invoice</h2></div>
                            <p class="font-mono text-xs tracking-[0.12em] text-ink/45">PS-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <dl class="mt-8 space-y-4 text-sm">
                            <div class="flex justify-between gap-4"><dt class="text-ink/55">Subtotal</dt><dd class="font-medium">RM{{ number_format($subtotal / 100, 2) }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-ink/55">Shipping</dt><dd class="font-medium">{{ $order->shipping == 0 ? 'Free' : 'RM' . number_format($order->shipping / 100, 2) }}</dd></div>
                            @if ($order->discount != 0)<div class="flex justify-between gap-4 text-moss"><dt>Discount</dt><dd>- RM{{ number_format($order->discount / 100, 2) }}</dd></div>@endif
                            <div class="my-5 h-px bg-ink/10"></div>
                            <div class="flex items-end justify-between gap-4"><dt class="font-display text-lg font-semibold">Amount paid</dt><dd class="font-display text-2xl font-semibold text-accent">RM{{ number_format($order->amount / 100, 2) }}</dd></div>
                        </dl>
                        <p class="mt-7 text-xs leading-5 text-ink/45">Paid {{ optional($order->paid_at)->format('d M Y · g:i A') ?? 'date unavailable' }}. This receipt is your order confirmation.</p>
                    </div>
                </div>

                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                    <div class="rounded-[calc(2rem-0.375rem)] bg-moss p-6 text-paper sm:p-8">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-paper/55">Shipping to</p>
                        <h2 class="mt-3 font-display text-2xl font-semibold tracking-[-0.04em]">{{ $order->name }}</h2>
                        <address class="mt-4 not-italic text-sm leading-6 text-paper/70">{{ $order->address }}<br>{{ $order->postcode }} · <span class="capitalize">{{ $order->state }}</span></address>
                        <div class="mt-7 flex items-center gap-2 text-xs text-paper/55"><span class="size-1.5 rounded-full bg-gold"></span>Updates will follow the order record.</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <x-flash />
</section>

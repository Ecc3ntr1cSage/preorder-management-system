@php
    $cover = $campaign->images->first();
    $backed = $campaign->orders->where('paid', true)->sum('quantity');
    $isOpen = $campaign->end_date->isFuture();
@endphp

<section class="relative isolate overflow-hidden bg-paper">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[42rem] bg-[radial-gradient(circle_at_8%_5%,rgba(105,115,91,.16),transparent_32%),radial-gradient(circle_at_90%_0%,rgba(232,111,81,.14),transparent_30%)]"></div>

    <div class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 sm:pt-16 lg:px-10 lg:pb-32">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(23rem,.9fr)] lg:items-start lg:gap-16">
            <div data-animate="fade-up">
                <div class="mb-7 flex flex-wrap items-center gap-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-moss">
                    <span class="inline-flex items-center gap-2 rounded-full bg-moss/10 px-3 py-1.5"><span class="size-1.5 rounded-full {{ $isOpen ? 'bg-moss' : 'bg-accent' }}"></span>{{ $isOpen ? 'Open for preorders' : 'Campaign ended' }}</span>
                    <span>{{ $campaign->user->name }} · community maker</span>
                </div>

                <h1 class="max-w-4xl font-display text-6xl font-semibold leading-[.9] tracking-[-0.075em] text-ink sm:text-8xl">{{ $campaign->title }}</h1>
                <p class="mt-7 max-w-2xl text-lg leading-8 text-ink/60 sm:text-xl">{{ $campaign->description }}</p>

                <div class="mt-8 flex flex-wrap gap-x-8 gap-y-3 text-xs font-semibold uppercase tracking-[0.16em] text-ink/45">
                    <span>Ends {{ $campaign->end_date->format('d M Y') }}</span>
                    <span>{{ $backed }} backed</span>
                    <span>{{ $campaign->currency }}{{ number_format($campaign->price / 100, 2) }} each</span>
                </div>
            </div>

            <div class="lg:pt-4" data-animate="fade-up" data-delay="160">
                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                    <div class="rounded-[calc(2rem-0.375rem)] bg-[#171412] p-5 text-paper shadow-[0_28px_90px_rgba(46,26,71,.16)] sm:p-7">
                        <div class="flex items-center justify-between gap-4">
                            <div><p class="text-[10px] uppercase tracking-[0.22em] text-paper/45">Back the next run</p><p class="mt-2 font-display text-2xl font-semibold tracking-[-0.04em]">Make it yours.</p></div>
                            <span class="rounded-full bg-gold/15 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-gold">{{ $campaign->currency }}</span>
                        </div>

                        <form wire:submit="preorder" class="mt-8 space-y-7">
                            @if ($this->hasVariations($campaign->variations))
                                @foreach ($campaign->variations as $variation)
                                    @if ($variation['name'] || $variation['values'])
                                        <fieldset class="space-y-3">
                                            <legend class="text-[10px] font-semibold uppercase tracking-[0.2em] text-paper/45">{{ $variation['name'] }}</legend>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach (explode(',', $variation['values']) as $value)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" wire:model="selectedVariations.{{ $variation['name'] }}" value="{{ trim($value) }}" class="peer sr-only" />
                                                        <span class="inline-flex rounded-full bg-paper/[.06] px-4 py-2.5 text-sm text-paper/65 ring-1 ring-paper/10 transition-all duration-700 ease-[cubic-bezier(.34,1.56,.64,1)] peer-checked:bg-gold peer-checked:text-ink peer-checked:ring-gold peer-checked:scale-[1.03]">{{ trim($value) }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <x-input-error for="selectedVariations" class="text-red-300" />
                                        </fieldset>
                                    @endif
                                @endforeach
                            @endif

                            <div class="flex items-end justify-between gap-5 border-t border-paper/10 pt-6">
                                <div><p class="text-[10px] uppercase tracking-[0.18em] text-paper/45">Per item</p><p class="mt-2 font-display text-4xl font-semibold tracking-[-0.06em] text-paper">{{ $campaign->currency }}{{ number_format($campaign->price / 100, 2) }}</p></div>
                                <label class="text-right text-[10px] uppercase tracking-[0.18em] text-paper/45">Quantity<input wire:model="quantity" type="number" min="1" class="mt-2 block w-20 rounded-full bg-paper/[.06] px-3 py-2.5 text-center text-sm text-paper ring-1 ring-paper/10 focus:ring-2 focus:ring-gold" aria-label="Quantity" /></label>
                            </div>

                            <button type="submit" class="group inline-flex w-full items-center justify-between gap-4 rounded-full bg-gold px-5 py-3.5 text-sm font-semibold text-ink transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:bg-paper active:scale-[.98]">
                                <span>Back this campaign</span><span class="flex size-8 items-center justify-center rounded-full bg-ink/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-px"><svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true"><path d="M5 12h13m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" /></svg></span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-3 text-center text-[10px] uppercase tracking-[0.16em] text-ink/45">
                    <span class="rounded-full bg-ink/5 px-3 py-2.5 ring-1 ring-ink/10">Secure checkout</span><span class="rounded-full bg-ink/5 px-3 py-2.5 ring-1 ring-ink/10">Small batch</span><span class="rounded-full bg-ink/5 px-3 py-2.5 ring-1 ring-ink/10">Direct maker</span>
                </div>
            </div>
        </div>

        <div class="mt-16 grid gap-4 md:grid-cols-3 md:grid-rows-2 md:gap-5" data-animate="fade-up" data-delay="220">
            @forelse ($campaign->images as $index => $image)
                <div class="group relative min-h-64 overflow-hidden rounded-[1.75rem] bg-ink/5 ring-1 ring-ink/10 {{ $index === 0 ? 'md:col-span-2 md:row-span-2 md:min-h-[42rem]' : 'md:min-h-80' }}">
                    <img src="{{ asset('storage/campaign/' . $image->image) }}" alt="{{ $campaign->title }} preview {{ $index + 1 }}" class="h-full w-full object-cover transition-transform duration-[1200ms] ease-[cubic-bezier(.32,.72,0,1)] group-hover:scale-105" />
                    @if ($index === 0)<div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div><span class="absolute bottom-5 left-5 rounded-full bg-paper/90 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-ink">{{ $isOpen ? 'Live now' : 'Archive' }}</span>@endif
                </div>
            @empty
                <div class="rounded-[1.75rem] bg-moss/10 p-10 text-moss md:col-span-2 md:row-span-2"><p class="text-[10px] uppercase tracking-[0.2em]">Campaign visual</p><p class="mt-4 font-display text-3xl font-semibold">{{ $campaign->title }}</p></div>
            @endforelse
        </div>

        <div class="mt-24 grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(20rem,.9fr)] lg:gap-20">
            <div class="space-y-10">
                <div data-animate="fade-up">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">The campaign</p>
                    <h2 class="mt-4 font-display text-4xl font-semibold tracking-[-0.06em] sm:text-5xl">Made with intention.</h2>
                    <div class="mt-7 max-w-2xl text-base leading-8 text-ink/65">
                        @if ($campaign->details)
                            {!! nl2br(e($campaign->details)) !!}
                        @else
                            <p>No detailed description available.</p>
                        @endif
                    </div>
                </div>

                <div class="h-px bg-ink/10"></div>

                <div data-animate="fade-up" data-delay="100">
                    <div class="flex items-end justify-between gap-5"><div><p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">Open conversation</p><h2 class="mt-3 font-display text-3xl font-semibold tracking-[-0.05em]">Questions for the maker</h2></div><span class="text-xs text-ink/45">{{ $campaign->questions->count() }} total</span></div>
                    <form wire:submit="enquiry" class="mt-7">
                        <div class="rounded-[1.5rem] bg-white/70 p-1.5 ring-1 ring-ink/10"><textarea wire:model="question" rows="3" placeholder="Ask something useful..." class="block w-full resize-none rounded-[calc(1.5rem-0.375rem)] bg-transparent px-5 py-4 text-sm text-ink outline-none placeholder:text-ink/35 focus:ring-2 focus:ring-accent/40"></textarea></div>
                        <x-input-error for="question" class="mt-2 text-red-500" />
                        <button type="submit" class="group mt-4 inline-flex items-center gap-3 rounded-full bg-ink px-4 py-2.5 text-sm font-semibold text-paper transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:bg-accent active:scale-[.98]">Ask the maker <span class="flex size-7 items-center justify-center rounded-full bg-paper/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] group-hover:translate-x-1"><svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true"><path d="m5 12 13-7-4.5 14-3-6.5L5 12Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round" /><path d="m10.5 12.5 3.5-2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" /></svg></span></button>
                    </form>

                    <div class="mt-8 space-y-3">
                        @forelse ($campaign->questions as $question)
                            <article class="rounded-[1.5rem] bg-ink/5 p-5 ring-1 ring-ink/10 transition-transform duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-moss">Question</p><p class="mt-3 text-sm leading-6 text-ink/75">{{ $question->question }}</p>
                                @if ($question->reply)<div class="mt-4 rounded-xl bg-moss/10 px-4 py-3"><p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-moss">Maker reply</p><p class="mt-2 text-sm leading-6 text-ink/65">{{ $question->reply->reply }}</p></div>@endif
                            </article>
                        @empty
                            <p class="text-sm text-ink/45">Be the first to ask a question.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-4 lg:pt-1" data-animate="fade-up" data-delay="160">
                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="rounded-[calc(2rem-0.375rem)] bg-moss p-7 text-paper sm:p-8"><p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-paper/55">How it works</p><ol class="mt-7 space-y-6"><li class="flex gap-4"><span class="font-mono text-xs text-gold">01</span><span class="text-sm leading-6 text-paper/75">Choose a variation and quantity.</span></li><li class="flex gap-4"><span class="font-mono text-xs text-gold">02</span><span class="text-sm leading-6 text-paper/75">Complete the secure local checkout.</span></li><li class="flex gap-4"><span class="font-mono text-xs text-gold">03</span><span class="text-sm leading-6 text-paper/75">Your backing helps the maker produce the next batch.</span></li></ol></div></div>
                <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10"><div class="rounded-[calc(2rem-0.375rem)] bg-white/70 p-7 shadow-[0_22px_60px_rgba(71,52,38,.07)] sm:p-8"><p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">Campaign note</p><p class="mt-4 text-sm leading-7 text-ink/60">Preorders turn a good idea into a production signal. Your order is a direct vote for this next run.</p><div class="mt-7 flex items-center gap-3 text-xs font-semibold text-ink/50"><span class="flex size-8 items-center justify-center rounded-full bg-accent/10 text-accent">↗</span>Made visible by pre.shop</div></div></div>
            </aside>
        </div>
    </div>
    <x-flash />
</section>

@inject('carbon', 'Carbon\Carbon')
<div>
    @php($cover = $campaign->images->first())
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-10 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_.9fr] lg:items-start lg:gap-16">
            <div class="space-y-4">
                <div class="relative aspect-[4/3] overflow-hidden rounded-[2rem] bg-ink">
                    <img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/checkout2.webp') }}" alt="{{ $campaign->title }} campaign" class="h-full w-full object-cover opacity-90">
                    <span class="absolute left-5 top-5 rounded-full bg-paper px-3 py-1.5 text-xs font-bold uppercase tracking-wider">{{ $campaign->end_date->isFuture() ? 'Open for preorders' : 'Campaign ended' }}</span>
                </div>
                @if ($campaign->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto">
                        @foreach ($campaign->images as $image)
                            <img src="{{ asset('storage/campaign/' . $image->image) }}" alt="{{ $campaign->title }} preview" class="h-20 w-20 flex-none rounded-xl object-cover ring-1 ring-ink/10">
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-moss">{{ $campaign->user->name }} · community maker</p>
                <h1 class="mt-3 font-display text-4xl font-semibold tracking-tight sm:text-6xl">{{ $campaign->title }}</h1>
                <p class="mt-5 text-lg leading-8 text-ink/65">{{ $campaign->description }}</p>
                <div class="mt-6 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-wider text-ink/60">
                    <span class="rounded-full bg-white px-3 py-2 ring-1 ring-ink/10">Ends {{ $campaign->end_date->format('d M Y') }}</span>
                    <span class="rounded-full bg-white px-3 py-2 ring-1 ring-ink/10">{{ $campaign->orders->where('paid', true)->sum('quantity') }} backed</span>
                </div>

                <form wire:submit="preorder" class="mt-8 rounded-2xl bg-ink p-6 text-paper sm:p-8">
                    @if ($this->hasVariations($campaign->variations))
                        @foreach ($campaign->variations as $variation)
                            @if ($variation['name'] || $variation['values'])
                                <fieldset class="mb-6">
                                    <legend class="text-sm font-semibold">{{ $variation['name'] }}</legend>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach (explode(',', $variation['values']) as $value)
                                            <label class="cursor-pointer">
                                                <input type="radio" wire:model="selectedVariations.{{ $variation['name'] }}" value="{{ trim($value) }}" class="peer sr-only">
                                                <span class="inline-flex rounded-lg border border-paper/25 px-3 py-2 text-sm peer-checked:border-accent peer-checked:bg-accent">{{ trim($value) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error for="selectedVariations" class="mt-2 text-red-300" />
                                </fieldset>
                            @endif
                        @endforeach
                    @endif
                    <div class="flex items-end justify-between gap-4 border-t border-paper/15 pt-6">
                        <div><p class="text-xs uppercase tracking-wider text-paper/55">Per item</p><p class="mt-1 font-display text-3xl font-semibold">RM {{ number_format($campaign->price / 100, 2) }}</p></div>
                        <label class="text-right text-xs uppercase tracking-wider text-paper/55">Quantity<input wire:model="quantity" type="number" min="1" class="mt-2 block w-20 rounded-lg border-0 bg-paper px-3 py-2 text-center text-ink focus:ring-2 focus:ring-accent" aria-label="Quantity"></label>
                    </div>
                    <x-button type="submit" class="mt-6 w-full bg-accent hover:bg-paper hover:text-ink" target="preorder">Back this campaign</x-button>
                </form>
            </div>
        </div>

        <div class="mt-16 grid gap-10 border-t border-ink/10 pt-10 lg:grid-cols-[1fr_.8fr]">
            <div class="space-y-10">
                <div><p class="text-xs font-bold uppercase tracking-[0.25em] text-moss">The idea</p><p class="mt-3 text-lg leading-8 text-ink/70">{{ $campaign->details }}</p></div>
                <div>
                    <div class="flex items-center justify-between"><h2 class="font-display text-2xl font-semibold">Questions</h2><span class="text-sm text-ink/50">{{ $campaign->questions->count() }}</span></div>
                    <form wire:submit="enquiry" class="mt-4 flex gap-2"><x-textarea wire:model="question" rows="2" placeholder="Ask the maker something" class="min-w-0 flex-1" /><button class="rounded-xl bg-ink px-4 py-2 text-sm font-semibold text-paper hover:bg-accent">Ask</button></form>
                    <x-input-error for="question" class="mt-2" />
                    <div class="mt-5 space-y-3">
                        @forelse ($campaign->questions as $question)
                            <article class="rounded-2xl bg-white p-5 ring-1 ring-ink/10"><p class="text-xs font-semibold uppercase tracking-wider text-moss">Question</p><p class="mt-2">{{ $question->question }}</p>@if ($question->reply)<p class="mt-3 border-l-2 border-accent pl-3 text-sm text-ink/60">{{ $question->reply->reply }}</p>@endif</article>
                        @empty
                            <p class="text-sm text-ink/50">Be the first to ask a question.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <aside class="rounded-2xl bg-moss p-6 text-paper sm:p-8"><p class="text-xs font-bold uppercase tracking-[0.25em] text-paper/60">How it works</p><ol class="mt-6 space-y-5 text-sm leading-6"><li><span class="mr-2 text-accent">01</span> Choose a variation and quantity.</li><li><span class="mr-2 text-accent">02</span> Complete the simulated checkout.</li><li><span class="mr-2 text-accent">03</span> The maker uses your support to produce the next batch.</li></ol></aside>
        </div>
    </section>
</div>

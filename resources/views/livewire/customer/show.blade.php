@inject('carbon', 'Carbon\Carbon')
<div class="relative isolate min-h-[100dvh] bg-ink text-paper overflow-hidden">
    <div class="absolute inset-0 bg-radial-mesh pointer-events-none"></div>
    <div class="absolute inset-0 grain opacity-[0.02] pointer-events-none"></div>

    <div class="absolute top-0 right-0 w-[600px] h-[600px] -translate-y-1/2 translate-x-1/3 bg-gradient-to-br from-gold/10 via-transparent to-deep-purple/20 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    @php($cover = $campaign->images->first())

    <section class="pt-24 pb-20 px-4 sm:px-6 lg:px-10 min-h-[100dvh] flex items-center">
        <div class="mx-auto w-full max-w-8xl">
            <div class="grid gap-8 lg:items-center lg:gap-12 xl:gap-20">
                <div class="space-y-8" data-animate="fade-up">
                    <div class="inline-flex items-center gap-2 rounded-full glass-white-5 px-4 py-1.5 text-xs font-medium text-gold">
                        <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>
                        <span>{{ $campaign->end_date->isFuture() ? 'Open for preorders' : 'Campaign ended' }}</span>
                    </div>

                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-gold/60">
                        {{ $campaign->user->name }} · community maker
                    </p>

                    <h1 class="font-display text-5xl font-bold leading-[1.05] tracking-[-0.03em] text-white sm:text-6xl lg:text-7xl">
                        {{ $campaign->title }}
                    </h1>

                    <p class="max-w-2xl text-lg leading-8 text-white/60">
                        {{ $campaign->description }}
                    </p>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <span class="inline-flex items-center gap-2 rounded-full glass-white-5 px-4 py-2 text-xs font-semibold text-white/70">
                            Ends {{ $campaign->end_date->format('d M Y') }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full glass-white-5 px-4 py-2 text-xs font-semibold text-white/70">
                            {{ $campaign->orders->where('paid', 'true')->sum('quantity') ?? 0 }} backed
                        </span>
                    </div>
                </div>

                <div class="relative" data-animate="fade-up" data-delay="200">
                    <div class="relative mx-auto w-full max-w-2xl">
                        <div class="relative group">
                            <div class="absolute inset-0 -translate-x-4 -translate-y-4 rounded-[3rem] bg-gradient-to-br from-gold/20 via-transparent to-deep-purple/30 blur-2xl opacity-40 group-hover:opacity-60 transition-opacity duration-700"></div>

                            @php($isotope = $campaign->images->count() > 1)
                            <div class="{{ $isotope ? 'isotope-grid' : 'overflow-hidden' }} relative rounded-[2.5rem] ring-1 ring-white/10">
                                @if ($isotope)
                                    @foreach ($campaign->images as $index => $image)
                                        @php($delay = $index * 100)
                                        <div class="isotope-item relative overflow-hidden rounded-[2.5rem]" data-delay="{{ $delay }}">
                                            <img src="{{ asset('storage/campaign/' . $image->image) }}"
                                                alt="{{ $campaign->title }} preview {{ $index + 1 }}"
                                                class="h-full w-full object-cover opacity-85 transition-transform duration-1000 hover:scale-105" />

                                            @if ($index === 0)
                                                <span class="absolute left-5 top-5 rounded-full glass-black-20 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gold">
                                                    {{ $campaign->end_date->isFuture() ? 'Live' : 'Ended' }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/checkout2.webp') }}"
                                        alt="{{ $campaign->title }} campaign"
                                        class="h-full w-full object-cover opacity-90 transition-transform duration-1000 hover:scale-105" />
                                    <span class="absolute left-5 top-5 rounded-full glass-black-20 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gold">
                                        {{ $campaign->end_date->isFuture() ? 'Live' : 'Ended' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($isotope)
                            <div class="mt-6 flex gap-3 overflow-x-auto pb-2">
                                @foreach ($campaign->images as $image)
                                    <img src="{{ asset('storage/campaign/' . $image->image) }}"
                                        alt="{{ $campaign->title }} preview thumbnail"
                                        class="h-16 w-16 flex-none rounded-xl object-cover ring-1 ring-white/10 transition-elastic hover:ring-gold/30" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-8xl">
            <div class="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-start lg:gap-16">
                <div class="space-y-12">
                    <div class="rounded-[2.5rem] glass-black-10 p-8 sm:p-12" data-animate="fade-up">
                        <div class="mb-8">
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-gold/60">The campaign</p>
                            <h2 class="mt-3 font-display text-3xl font-bold text-white">Details</h2>
                        </div>

                        <div class="prose prose-invert max-w-none">
                            @if ($campaign->details)
                                {!! nl2br(e($campaign->details)) !!}
                            @else
                                <p class="text-white/60">No detailed description available.</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] glass-black-10 p-8 sm:p-12" data-animate="fade-up" data-delay="150">
                        <div class="mb-8">
                            <h2 class="font-display text-3xl font-bold text-white">Questions</h2>
                            <p class="mt-2 text-sm text-white/50">{{ $campaign->questions->count() }} questions</p>
                        </div>

                        <form wire:submit="enquiry" class="mb-6">
                            <div class="relative">
                                <textarea wire:model="question" rows="3"
                                    placeholder="Ask the maker something..."
                                    class="peer w-full resize-none rounded-[1.5rem] bg-white/5 px-6 py-4 text-sm text-white placeholder-transparent focus:outline-none focus:ring-2 focus:ring-gold/50"></textarea>
                                <label
                                    class="pointer-events-none absolute left-4 top-3 text-xs font-medium text-white/50 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-1 peer-focus:text-xs peer-focus:font-medium peer-focus:text-gold">
                                    Your question
                                </label>
                            </div>
                            <x-input-error for="question" class="mt-2 text-red-300" />

                            <button type="submit"
                                class="group mt-4 inline-flex items-center justify-center gap-1 overflow-hidden rounded-full bg-gold px-6 py-3 text-sm font-bold text-ink transition-elastic-soft hover:bg-gold/90 hover:scale-95">
                                Ask question
                                <span class="relative z-10 flex h-7 w-7 items-center justify-center rounded-full bg-ink/10 text-xs transition-transform group-hover:translate-x-1 group-hover:scale-110">
                                    ↗
                                </span>
                            </button>
                        </form>

                        <div class="space-y-5">
                            @forelse ($campaign->questions as $question)
                                <div class="rounded-[1.5rem] glass-white-5 p-5 transition-elastic hover:ring-1 hover:ring-gold/20" data-animate="fade-up">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gold">Question</p>
                                    <p class="mt-2 text-sm text-white/80">{{ $question->question }}</p>

                                    @if ($question->reply)
                                        <div class="mt-3 border-l-2 border-gold pl-3">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Reply</p>
                                            <p class="mt-1 text-sm text-white/60">{{ $question->reply->reply }}</p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-white/50">Be the first to ask a question.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="rounded-[2.5rem] glass-black-10 p-8 sm:p-10" data-animate="fade-up" data-delay="100">
                        <form wire:submit="preorder" class="space-y-6">
                            @if ($this->hasVariations($campaign->variations))
                                @foreach ($campaign->variations as $variation)
                                    @if ($variation['name'] || $variation['values'])
                                        <fieldset class="space-y-2">
                                            <legend class="text-xs font-semibold uppercase tracking-wider text-white/50">
                                                {{ $variation['name'] }}
                                            </legend>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach (explode(',', $variation['values']) as $value)
                                                    <label class="cursor-pointer">
                                                        <input type="radio"
                                                            wire:model="selectedVariations.{{ $variation['name'] }}"
                                                            value="{{ trim($value) }}"
                                                            class="peer sr-only" />
                                                        <span
                                                            class="inline-flex rounded-xl border border-white/10 px-4 py-2.5 text-sm text-white/70 peer-checked:border-gold peer-checked:bg-gold peer-checked:text-ink transition-elastic-soft peer-checked:scale-105">
                                                            {{ trim($value) }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <x-input-error for="selectedVariations" class="mt-1 text-red-300" />
                                        </fieldset>
                                    @endif
                                @endforeach
                            @endif

                            <div class="flex items-end justify-between gap-4 border-t border-white/10 pt-6">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-white/50">Per item</p>
                                    <p class="mt-1 font-display text-3xl font-bold text-white">
                                        RM {{ number_format($campaign->price / 100, 2) }}
                                    </p>
                                </div>

                                <label class="text-right text-xs uppercase tracking-wider text-white/50">
                                    Quantity
                                    <input wire:model="quantity" type="number" min="1"
                                        class="mt-2 block w-20 rounded-xl border-0 bg-white/5 px-3 py-2 text-center text-white focus:ring-2 focus:ring-gold"
                                        aria-label="Quantity" />
                                </label>
                            </div>

                            <button type="submit"
                                class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-full bg-ink px-6 py-4 text-sm font-bold text-white ring-1 ring-white/5 transition-elastic-soft hover:scale-95 hover:ring-gold/50">
                                <span class="absolute inset-0 -translate-x-full group-hover:translate-x-full bg-gradient-to-r from-transparent via-gold/20 to-transparent transition-transform duration-1000 ease-in-out"></span>
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    Back this campaign
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-xs transition-transform group-hover:translate-x-1 group-hover:scale-110">
                                        ↗
                                    </span>
                                </span>
                            </button>
                        </form>
                    </div>

                    <div class="rounded-[2.5rem] glass-black-10 p-6 sm:p-8" data-animate="fade-up" data-delay="200">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-gold/60">How it works</p>
                        <ol class="mt-6 space-y-5 text-sm">
                            <li class="flex gap-3">
                                <span class="font-display text-xl font-bold text-gold">01</span>
                                <span class="text-white/70">Choose a variation and quantity.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="font-display text-xl font-bold text-gold">02</span>
                                <span class="text-white/70">Complete the secure checkout via FPX.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="font-display text-xl font-bold text-gold">03</span>
                                <span class="text-white/70">The maker uses your support to produce the next batch.</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.isotope-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
}

.isotope-item {
    position: relative;
    width: 100%;
    height: 100%;
}

@media (min-width: 768px) {
    .isotope-grid {
        display: block;
    }

    .isotope-item {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 700ms cubic-bezier(0.32, 0.72, 0, 1);
    }

    .isotope-item:first-child {
        opacity: 1;
    }

    .isotope-item:not(:first-child) {
        cursor: pointer;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isotopeItems = document.querySelectorAll('.isotope-item');

    if (isotopeItems.length > 1) {
        isotopeItems.forEach((item, index) => {
            if (index > 0) {
                item.addEventListener('click', function() {
                    isotopeItems.forEach(i => {
                        i.classList.remove('opacity-100');
                        i.style.opacity = '0';
                    });
                    this.style.opacity = '1';
                    this.classList.add('opacity-100');
                });
            }
        });
    }
});
</script>

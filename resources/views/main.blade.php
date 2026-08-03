@php
    $campaigns = \App\Models\Campaign::with(['images', 'user', 'orders'])
        ->where('status', 1)
        ->where('end_date', '>', now())
        ->latest()
        ->take(4)
        ->get();
@endphp

<x-guest-layout>
    <div class="relative isolate min-h-[100dvh] overflow-hidden bg-paper text-ink">
        <div class="pointer-events-none fixed inset-0 z-50 grain opacity-[0.035] mix-blend-multiply"></div>
        <div class="pointer-events-none absolute -left-32 top-40 h-[32rem] w-[32rem] rounded-full bg-moss/10 blur-[100px]"></div>
        <div class="pointer-events-none absolute right-[-12rem] top-[-10rem] h-[38rem] w-[38rem] rounded-full bg-accent/10 blur-[120px]"></div>

        <header class="relative z-40 px-4 pt-5 sm:px-6 lg:px-8">
            <nav x-data="{ open: false }" class="relative mx-auto flex max-w-6xl items-center justify-between rounded-full bg-ink px-3 py-3 text-paper ring-1 ring-ink/10 sm:px-5">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5" aria-label="pre.shop home">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-paper/10 ring-1 ring-paper/15 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:rotate-12">
                        <img src="{{ asset('asset/preorder.png') }}" alt="" class="h-6 w-6 object-contain" />
                    </span>
                    <span class="font-display text-lg font-semibold tracking-[-0.04em]">pre<span class="text-accent">.</span>shop</span>
                </a>

                <div class="hidden items-center gap-7 text-xs font-medium tracking-wide text-paper/60 md:flex">
                    <a href="#how-it-works" class="transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-paper">How it works</a>
                    <a href="#campaigns" class="transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-paper">Campaigns</a>
                    <a href="#makers" class="transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-paper">For makers</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-2 rounded-full bg-accent px-4 py-2.5 font-semibold text-white transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-0.5 hover:bg-accent-dark active:scale-[0.98]">
                            Workspace
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white/15 text-[11px] transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:translate-x-0.5 group-hover:-translate-y-px">↗</span>
                        </a>
                    @else
                        <button type="button" x-on:click="loginOpen = true" class="group inline-flex items-center gap-2 rounded-full bg-paper px-4 py-2.5 font-semibold text-ink transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-0.5 hover:bg-white active:scale-[0.98]">
                            Log in
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-ink/10 text-[11px] transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:translate-x-0.5 group-hover:-translate-y-px">↗</span>
                        </button>
                    @endauth
                </div>

                <div class="flex items-center gap-2 md:hidden">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-full bg-accent px-3.5 py-2 text-[11px] font-semibold text-white">Workspace</a>
                    @else
                        <button type="button" x-on:click="loginOpen = true" class="rounded-full bg-paper px-3.5 py-2 text-[11px] font-semibold text-ink">Log in</button>
                    @endauth
                    <button type="button" x-on:click="open = !open" x-bind:aria-expanded="open" aria-label="Toggle menu" class="relative flex h-9 w-9 items-center justify-center rounded-full bg-paper/10 ring-1 ring-paper/15">
                        <span class="absolute h-px w-4 bg-paper transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]" :class="open ? 'rotate-45' : '-translate-y-1.5'"></span>
                        <span class="absolute h-px w-4 bg-paper transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]" :class="open ? '-rotate-45' : 'translate-y-1.5'"></span>
                    </button>
                </div>

                <div x-cloak x-show="open" x-transition:enter="transition duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-2 opacity-0" class="absolute inset-x-0 top-full mt-3 rounded-[1.75rem] bg-ink p-3 ring-1 ring-paper/10 md:hidden">
                    <a href="#how-it-works" x-on:click="open = false" class="block rounded-full px-4 py-3 text-sm text-paper/70 transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-paper/10 hover:text-paper">How it works</a>
                    <a href="#campaigns" x-on:click="open = false" class="block rounded-full px-4 py-3 text-sm text-paper/70 transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-paper/10 hover:text-paper">Campaigns</a>
                    <a href="#makers" x-on:click="open = false" class="block rounded-full px-4 py-3 text-sm text-paper/70 transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-paper/10 hover:text-paper">For makers</a>
                </div>
            </nav>
        </header>

        <div>
            <section class="px-4 pb-28 pt-20 sm:px-6 sm:pb-36 sm:pt-28 lg:px-8 lg:pt-32">
                <div class="mx-auto grid max-w-6xl items-center gap-14 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
                    <div data-animate="fade-up" class="max-w-xl">
                        <div class="mb-7 inline-flex items-center gap-2 rounded-full bg-ink px-3.5 py-2 text-[10px] font-semibold uppercase tracking-[0.24em] text-paper/75">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Malaysia's community preorder marketplace
                        </div>
                        <h1 class="max-w-2xl font-display text-[3.75rem] font-semibold leading-[0.94] tracking-[-0.075em] text-ink sm:text-7xl lg:text-[6.8rem]">
                            Make the<br /><span class="font-serif font-normal italic text-moss">next thing</span><br />together.
                        </h1>
                        <p class="mt-8 max-w-md text-base leading-7 text-ink/60 sm:text-lg">
                            Pre.shop turns early ideas into real demand. Back thoughtful products, or give your own idea a place to begin.
                        </p>
                        <div class="mt-9 flex flex-wrap items-center gap-4">
                            <a href="{{ route('customer.shop') }}" class="group inline-flex items-center gap-2 rounded-full bg-accent px-5 py-3.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-1 hover:bg-accent-dark active:scale-[0.98]">
                                Explore campaigns
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/15 text-sm transition-transform duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:translate-x-1 group-hover:-translate-y-0.5">↗</span>
                            </a>
                            <a href="#how-it-works" class="group inline-flex items-center gap-2 px-1 py-3 text-sm font-semibold text-ink/60 transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-ink">
                                See how it works
                                <span class="transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:translate-x-1">→</span>
                            </a>
                        </div>
                    </div>

                    <div data-animate="fade-up" data-delay="150" class="relative mx-auto w-full max-w-[34rem] lg:ml-auto">
                        <div class="absolute -left-6 top-12 hidden h-36 w-36 rounded-full border border-moss/25 sm:block"></div>
                        <div class="absolute -right-3 bottom-10 h-24 w-24 rounded-full bg-accent/20 blur-2xl"></div>
                        <div class="rounded-[2.75rem] bg-ink/5 p-2 ring-1 ring-ink/10">
                            <div class="relative overflow-hidden rounded-[2.35rem] bg-[#d9c7ae] shadow-[0_30px_90px_rgba(60,48,28,0.16)]">
                                <img src="{{ asset('asset/product/chili-oil.png') }}" alt="Small-batch chili oil in a glass jar" class="h-[30rem] w-full object-cover transition-transform duration-[1200ms] ease-[cubic-bezier(0.32,0.72,0,1)] hover:scale-105 sm:h-[38rem]" />
                                <div class="absolute inset-0 bg-gradient-to-t from-ink/65 via-transparent to-transparent"></div>
                                <div class="absolute left-6 top-6 flex items-center gap-2 rounded-full bg-paper/90 px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-ink">
                                    <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                                    A live idea
                                </div>
                                <div class="absolute inset-x-6 bottom-6 rounded-[1.5rem] bg-ink/90 p-5 text-paper ring-1 ring-paper/15 sm:inset-x-8 sm:bottom-8 sm:p-6">
                                    <div class="flex items-end justify-between gap-4">
                                        <div>
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-paper/55">Featured study</p>
                                            <p class="mt-2 font-serif text-2xl italic">Small-batch, shared early.</p>
                                        </div>
                                        <span class="rounded-full bg-accent px-3 py-1.5 text-xs font-semibold text-white">64 backers</span>
                                    </div>
                                    <div class="mt-5 flex items-center justify-between border-t border-paper/15 pt-4 text-xs text-paper/55">
                                        <span>From first pour to first batch</span>
                                        <span class="font-semibold text-paper">RM 48.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-5 -left-4 rounded-[1.25rem] bg-moss px-4 py-3 text-xs font-semibold text-paper ring-4 ring-paper sm:-left-8">
                            made with intent
                        </div>
                    </div>
                </div>
            </section>

            <section class="border-y border-ink/10 bg-ink px-4 py-6 text-paper sm:px-6 lg:px-8">
                <div class="mx-auto grid max-w-6xl gap-6 text-sm sm:grid-cols-3 sm:gap-0">
                    <div class="flex items-center gap-3 sm:border-r sm:border-paper/15 sm:px-8 sm:first:pl-0">
                        <span class="font-serif text-3xl italic text-accent">01</span>
                        <span class="text-paper/60">A clearer signal<br /><strong class="font-semibold text-paper">before you build</strong></span>
                    </div>
                    <div class="flex items-center gap-3 sm:border-r sm:border-paper/15 sm:px-8">
                        <span class="font-serif text-3xl italic text-accent">02</span>
                        <span class="text-paper/60">A better way<br /><strong class="font-semibold text-paper">to back makers</strong></span>
                    </div>
                    <div class="flex items-center gap-3 sm:px-8 sm:last:pr-0">
                        <span class="font-serif text-3xl italic text-accent">03</span>
                        <span class="text-paper/60">Local ideas<br /><strong class="font-semibold text-paper">with a real runway</strong></span>
                    </div>
                </div>
            </section>

            <section id="how-it-works" class="px-4 py-28 sm:px-6 sm:py-36 lg:px-8">
                <div class="mx-auto max-w-6xl">
                    <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
                        <div data-animate="fade-up">
                            <span class="inline-flex rounded-full bg-moss/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">The simple version</span>
                            <h2 class="mt-5 max-w-md font-display text-4xl font-semibold leading-[0.98] tracking-[-0.06em] sm:text-6xl">From rough idea to something real.</h2>
                            <p class="mt-6 max-w-sm text-base leading-7 text-ink/55">A good preorder is a small promise made visible: show the idea, find the people, then make the thing.</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2" data-animate="fade-up" data-delay="120">
                            @foreach ([
                                ['num' => '01', 'title' => 'Put the idea on the table', 'desc' => 'Give your product a clear page, a fair price, and a finish line.', 'tone' => 'bg-[#e8d8c4]'],
                                ['num' => '02', 'title' => 'Let people vote with intent', 'desc' => 'Support arrives as real orders, not vanity metrics or vague likes.', 'tone' => 'bg-[#c9d1bd]'],
                                ['num' => '03', 'title' => 'Make the first batch count', 'desc' => 'Use the signal to produce with confidence and ship to the people who believed first.', 'tone' => 'bg-[#ded7cc]'],
                            ] as $step)
                                <article class="group rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10 {{ $loop->last ? 'sm:col-span-2' : '' }}">
                                    <div class="{{ $step['tone'] }} h-full rounded-[1.65rem] p-6 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:-translate-y-1 group-hover:shadow-[0_24px_50px_rgba(37,39,26,0.10)] sm:p-7">
                                        <div class="flex items-start justify-between gap-4">
                                            <span class="font-serif text-4xl italic text-ink/45">{{ $step['num'] }}</span>
                                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-ink/10 text-lg text-ink/70">↗</span>
                                        </div>
                                        <h3 class="mt-12 max-w-xs font-display text-xl font-semibold tracking-[-0.04em]">{{ $step['title'] }}</h3>
                                        <p class="mt-3 max-w-sm text-sm leading-6 text-ink/60">{{ $step['desc'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            @if ($campaigns->count() > 0)
                @php($featured = $campaigns->first())
                <section id="campaigns" class="bg-[#e7e0d4] px-4 py-28 sm:px-6 sm:py-36 lg:px-8">
                    <div class="mx-auto max-w-6xl">
                        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end" data-animate="fade-up">
                            <div>
                                <span class="inline-flex rounded-full bg-ink px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.22em] text-paper/70">Open for backing</span>
                                <h2 class="mt-5 font-display text-4xl font-semibold leading-none tracking-[-0.06em] sm:text-6xl">Ideas in motion.</h2>
                            </div>
                            <a href="{{ route('customer.shop') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-ink/65 transition-colors duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-ink">
                                View all campaigns
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-ink/10 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:translate-x-1">↗</span>
                            </a>
                        </div>

                        <div class="mt-12 grid gap-5 lg:grid-cols-12 lg:grid-rows-2" data-animate="fade-up" data-delay="120">
                            <a href="{{ route('customer.show', $featured->slug) }}" class="group relative min-h-[32rem] overflow-hidden rounded-[2.5rem] bg-ink p-2 ring-1 ring-ink/10 lg:col-span-7 lg:row-span-2">
                                <div class="relative h-full overflow-hidden rounded-[2.15rem]">
                                    @php($cover = $featured->images->first())
                                    <img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/product/field-notes.png') }}" alt="{{ $featured->title }}" class="absolute inset-0 h-full w-full object-cover opacity-90 transition-transform duration-[1200ms] ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-105" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/15 to-transparent"></div>
                                    <div class="absolute left-6 top-6 rounded-full bg-paper/90 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-ink">Featured campaign</div>
                                    <div class="absolute inset-x-6 bottom-6 text-paper sm:inset-x-8 sm:bottom-8">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-paper/55">{{ $featured->user->name ?? 'Independent maker' }} · maker</p>
                                        <h3 class="mt-3 max-w-lg font-display text-3xl font-semibold leading-none tracking-[-0.05em] sm:text-5xl">{{ Str::limit($featured->title, 42) }}</h3>
                                        <p class="mt-4 max-w-md text-sm leading-6 text-paper/65">{{ Str::limit($featured->description, 120) }}</p>
                                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
                                            <span class="rounded-full bg-accent px-3 py-1.5 font-semibold text-white">RM {{ number_format($featured->price / 100, 2) }}</span>
                                            <span class="text-paper/60">{{ $featured->orders->where('paid', true)->sum('quantity') }} backed</span>
                                            <span class="text-paper/40">Ends {{ \Carbon\Carbon::parse($featured->end_date)->format('d M') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            @foreach ($campaigns->slice(1) as $campaign)
                                <a href="{{ route('customer.show', $campaign->slug) }}" class="group relative min-h-[15rem] overflow-hidden rounded-[2rem] bg-paper p-1.5 ring-1 ring-ink/10 lg:col-span-5">
                                    <div class="relative h-full overflow-hidden rounded-[1.6rem] bg-ink">
                                        @php($cover = $campaign->images->first())
                                        <img src="{{ $cover ? asset('storage/campaign/' . $cover->image) : asset('asset/product/tote.png') }}" alt="{{ $campaign->title }}" class="absolute inset-0 h-full w-full object-cover opacity-80 transition-transform duration-[1200ms] ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-105" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent"></div>
                                        <div class="absolute inset-x-5 bottom-5 text-paper">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-paper/55">{{ $campaign->user->name ?? 'Independent maker' }}</p>
                                            <h3 class="mt-2 font-display text-xl font-semibold leading-none tracking-[-0.04em]">{{ Str::limit($campaign->title, 30) }}</h3>
                                            <p class="mt-3 text-xs text-paper/65">RM {{ number_format($campaign->price / 100, 2) }} <span class="mx-1 text-paper/35">·</span> View campaign ↗</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <section id="makers" class="px-4 py-28 sm:px-6 sm:py-36 lg:px-8">
                <div class="mx-auto max-w-6xl">
                    <div class="grid gap-5 lg:grid-cols-12">
                        <div class="rounded-[2.5rem] bg-moss p-8 text-paper sm:p-12 lg:col-span-7" data-animate="fade-up">
                            <span class="inline-flex rounded-full bg-paper/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.22em] text-paper/70">For the people making</span>
                            <h2 class="mt-8 max-w-xl font-display text-4xl font-semibold leading-[0.97] tracking-[-0.06em] sm:text-6xl">A little certainty goes a long way.</h2>
                            <p class="mt-6 max-w-md text-base leading-7 text-paper/65">Start a campaign, learn what your community wants, and put your energy into the version worth making.</p>
                            <button type="button" x-on:click="loginOpen = true" class="group mt-9 inline-flex items-center gap-2 rounded-full bg-paper px-5 py-3.5 text-sm font-semibold text-ink transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-1 hover:bg-white active:scale-[0.98]">
                                Open your workspace
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-ink/10 transition-transform duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:translate-x-1 group-hover:-translate-y-0.5">↗</span>
                            </button>
                        </div>
                        <div class="rounded-[2.5rem] bg-ink p-2 text-paper ring-1 ring-ink/10 lg:col-span-5" data-animate="fade-up" data-delay="120">
                            <div class="relative h-full min-h-[24rem] overflow-hidden rounded-[2.1rem] bg-[#25271d]">
                                <img src="{{ asset('asset/product/field-notes.png') }}" alt="A field notebook ready for a new idea" class="absolute inset-0 h-full w-full object-cover opacity-75 mix-blend-screen transition-transform duration-[1200ms] ease-[cubic-bezier(0.32,0.72,0,1)] hover:scale-105" />
                                <div class="absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent"></div>
                                <div class="absolute inset-x-6 bottom-6">
                                    <p class="font-serif text-3xl italic text-paper">Leave room for the next version.</p>
                                    <div class="mt-5 flex items-center justify-between border-t border-paper/15 pt-4 text-[10px] uppercase tracking-[0.18em] text-paper/50">
                                        <span>pre.shop / 2026</span>
                                        <span>Keep going →</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="border-t border-ink/10 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-6xl flex-col justify-between gap-4 text-xs text-ink/50 sm:flex-row sm:items-center">
                <span class="font-display text-sm font-semibold tracking-[-0.03em] text-ink">pre<span class="text-accent">.</span>shop</span>
                <span>Ideas with a waiting list · © {{ date('Y') }}</span>
            </div>
        </footer>
    </div>
</x-guest-layout>

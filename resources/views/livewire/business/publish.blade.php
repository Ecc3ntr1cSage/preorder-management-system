<x-dashboard-panel>
    <div
        x-data="{
            title: @entangle('title'),
            description: @entangle('description'),
            details: @entangle('details'),
            currency: @entangle('currency'),
            price: @entangle('price'),
            startDate: @entangle('startDate'),
            endDate: @entangle('endDate'),
            previews: [],
        }"
        class="-m-2 min-h-screen bg-paper p-4 sm:-m-6 sm:p-6 lg:-m-6 lg:p-10"
    >
        <div class="mx-auto mb-10 flex max-w-7xl flex-col gap-4 border-b border-ink/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-moss">Campaign studio</p>
                <h1 class="mt-2 font-display text-3xl font-semibold tracking-tight text-ink">Make the idea easy to back.</h1>
            </div>
            <a href="{{ route('business.manage') }}" class="text-sm font-semibold text-ink/60 underline decoration-ink/20 underline-offset-4 hover:text-accent">Back to campaigns</a>
        </div>

        <form wire:submit.prevent="campaign" enctype="multipart/form-data" class="mx-auto max-w-7xl">
            @csrf

            <div class="mb-8 flex flex-col gap-4 border-b border-ink/10 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-accent">01 / publish a campaign</p>
                    <p class="mt-3 max-w-xl text-base leading-7 text-ink/60">Shape the story, set the terms, and give your community a clear reason to say yes.</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-ink/10 bg-white/60 px-3 py-2 text-xs font-bold uppercase tracking-[0.16em] text-ink/50">
                    <span class="size-2 rounded-full bg-moss"></span>
                    Draft
                </span>
            </div>

            <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1.15fr)_minmax(19rem,.85fr)]">
                <div class="space-y-6">
                    <section class="rounded-3xl border border-ink/10 bg-white/75 p-5 shadow-sm sm:p-7">
                        <div class="mb-6 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-moss">The idea</p>
                                <h2 class="mt-2 font-display text-2xl font-semibold">Start with the point of view.</h2>
                            </div>
                            <span class="font-mono text-xs text-ink/35">01</span>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="campaign-title" class="block text-sm font-semibold text-ink">Campaign title</label>
                                <input id="campaign-title" type="text" x-model="title" required autocomplete="off" placeholder="e.g. Sunday market tote" class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                <x-input-error for="title" class="mt-2" />
                            </div>

                            <div>
                                <label for="campaign-description" class="block text-sm font-semibold text-ink">Short description</label>
                                <p class="mt-1 text-sm text-ink/50">The one-sentence version people will remember.</p>
                                <textarea id="campaign-description" x-model="description" required rows="3" placeholder="A sturdy canvas tote for the long way home." class="mt-2 block w-full resize-y rounded-xl border border-ink/15 bg-paper/60 px-4 py-3 text-base leading-7 text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent"></textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <div>
                                <label for="campaign-details" class="block text-sm font-semibold text-ink">Campaign details</label>
                                <p class="mt-1 text-sm text-ink/50">Tell backers what makes it worth waiting for.</p>
                                <textarea id="campaign-details" x-model="details" required rows="6" placeholder="Share the material, process, story, or promise behind the product." class="mt-2 block w-full resize-y rounded-xl border border-ink/15 bg-paper/60 px-4 py-3 text-base leading-7 text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent"></textarea>
                                <x-input-error for="details" class="mt-2" />
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-ink/10 bg-white/75 p-5 shadow-sm sm:p-7" x-data>
                        <div class="mb-6 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-moss">The first impression</p>
                                <h2 class="mt-2 font-display text-2xl font-semibold">Show people what they’re backing.</h2>
                            </div>
                            <span class="font-mono text-xs text-ink/35">02</span>
                        </div>

                        <label for="campaign-images" class="flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-ink/15 bg-paper/55 px-5 py-7 text-center hover:border-accent hover:bg-accent/5">
                            <span class="grid size-12 place-items-center rounded-2xl bg-ink text-paper">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-6" stroke="currentColor" stroke-width="1.7">
                                    <path d="M4 16.5V19a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2.5M12 4v11m0-11 4 4m-4-4L8 8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span class="mt-4 text-sm font-bold text-ink">Choose up to five campaign images</span>
                            <span id="campaign-images-help" class="mt-1 text-sm text-ink/50">JPG, PNG, or WEBP · 4MB max each</span>
                        </label>
                        <input id="campaign-images" type="file" wire:model="image" x-on:change="previews = Array.from($event.target.files).slice(0, 5).map(file => ({ url: URL.createObjectURL(file), name: file.name }))" accept="image/*" multiple aria-describedby="campaign-images-help" class="sr-only" />
                        <p wire:loading wire:target="image" class="mt-3 text-sm font-semibold text-accent">Uploading images…</p>
                        <x-input-error for="image.*" class="mt-3" />

                        <div x-cloak x-show="previews.length" class="mt-4 grid grid-cols-5 gap-2" aria-label="Selected campaign images">
                            <template x-for="(preview, index) in previews" :key="index">
                                <img :src="preview.url" :alt="preview.name" class="aspect-square w-full rounded-xl object-cover" />
                            </template>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-ink/10 bg-white/75 p-5 shadow-sm sm:p-7">
                        <div class="mb-6 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-moss">The terms</p>
                                <h2 class="mt-2 font-display text-2xl font-semibold">Make the commitment clear.</h2>
                            </div>
                            <span class="font-mono text-xs text-ink/35">03</span>
                        </div>

                        <div class="grid gap-5 md:grid-cols-3">
                            <div>
                                <label for="campaign-currency" class="block text-sm font-semibold text-ink">Currency</label>
                                <select id="campaign-currency" x-model="currency" class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink focus:border-accent focus:ring-accent">
                                    <option value="RM">RM</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="campaign-price" class="block text-sm font-semibold text-ink">Backer price</label>
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-sm font-bold text-ink/45" x-text="currency"></span>
                                    <input id="campaign-price" type="number" min="0" step="0.01" inputmode="decimal" x-model="price" required placeholder="48.00" class="block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 pl-12 pr-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                </div>
                                <x-input-error for="price" class="mt-2" />
                            </div>
                            <div>
                                <label for="campaign-start-date" class="block text-sm font-semibold text-ink">Starts</label>
                                <input id="campaign-start-date" type="date" x-model="startDate" min="{{ date('Y-m-d') }}" required class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink focus:border-accent focus:ring-accent" />
                                <x-input-error for="startDate" class="mt-2" />
                            </div>
                            <div>
                                <label for="campaign-end-date" class="block text-sm font-semibold text-ink">Ends</label>
                                <input id="campaign-end-date" type="date" x-model="endDate" min="{{ date('Y-m-d') }}" required class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink focus:border-accent focus:ring-accent" />
                                <x-input-error for="endDate" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-7 border-t border-ink/10 pt-6" x-data="{ shipping: 'free' }">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-sm font-bold">Shipping</h3>
                                    <p class="mt-1 text-sm text-ink/50">Choose how the final price is explained.</p>
                                </div>
                                <div class="inline-flex rounded-xl border border-ink/10 bg-paper/70 p-1" role="group" aria-label="Shipping options">
                                    <button type="button" x-on:click="shipping = 'free'" x-bind:aria-pressed="shipping === 'free'" x-bind:class="shipping === 'free' ? 'bg-ink text-paper' : 'text-ink/60 hover:text-ink'" class="min-h-10 cursor-pointer rounded-lg px-3 text-sm font-semibold">Free shipping</button>
                                    <button type="button" x-on:click="shipping = 'paid'" x-bind:aria-pressed="shipping === 'paid'" x-bind:class="shipping === 'paid' ? 'bg-ink text-paper' : 'text-ink/60 hover:text-ink'" class="min-h-10 cursor-pointer rounded-lg px-3 text-sm font-semibold">Add shipping</button>
                                </div>
                            </div>

                            <div x-cloak x-show="shipping === 'paid'" x-transition class="mt-4 grid gap-4 md:grid-cols-3">
                                <div>
                                    <label for="shipping-west" class="block text-sm font-semibold text-ink">West Malaysia</label>
                                    <input id="shipping-west" type="number" min="0" step="0.01" inputmode="decimal" wire:model="shipping.west_malaysia" placeholder="6.00" class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                    <x-input-error for="shipping.west_malaysia" class="mt-2" />
                                </div>
                                <div>
                                    <label for="shipping-sarawak" class="block text-sm font-semibold text-ink">Sarawak</label>
                                    <input id="shipping-sarawak" type="number" min="0" step="0.01" inputmode="decimal" wire:model="shipping.sarawak" placeholder="12.00" class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                    <x-input-error for="shipping.sarawak" class="mt-2" />
                                </div>
                                <div>
                                    <label for="shipping-sabah" class="block text-sm font-semibold text-ink">Sabah</label>
                                    <input id="shipping-sabah" type="number" min="0" step="0.01" inputmode="decimal" wire:model="shipping.sabah" placeholder="12.00" class="mt-2 block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                    <x-input-error for="shipping.sabah" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-7 border-t border-ink/10 pt-6" x-data="{ variations: @entangle('variations') }">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <h3 class="text-sm font-bold">Product variations <span class="font-normal text-ink/45">(optional)</span></h3>
                                    <p class="mt-1 text-sm text-ink/50">Give backers a simple choice, like size or finish.</p>
                                </div>
                                <button type="button" x-on:click="variations.push({ name: '', values: '' })" class="min-h-11 cursor-pointer rounded-xl border border-ink/15 px-4 text-sm font-bold hover:border-accent hover:text-accent">Add variation</button>
                            </div>
                            <div class="mt-4 space-y-3">
                                <template x-for="(variation, index) in variations" :key="index">
                                    <div class="grid grid-cols-[minmax(0,.8fr)_minmax(0,1.2fr)_2.75rem] gap-2">
                                        <div>
                                            <label :for="`variation-name-${index}`" class="sr-only">Variation name</label>
                                            <input :id="`variation-name-${index}`" x-model="variation.name" type="text" aria-label="Variation name" placeholder="Size" class="block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                        </div>
                                        <div>
                                            <label :for="`variation-values-${index}`" class="sr-only">Variation options</label>
                                            <input :id="`variation-values-${index}`" x-model="variation.values" type="text" aria-label="Variation options" placeholder="XS, S, M, L" class="block min-h-12 w-full rounded-xl border border-ink/15 bg-paper/60 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent" />
                                        </div>
                                        <button type="button" x-on:click="variations.splice(index, 1)" aria-label="Remove variation" class="grid min-h-12 min-w-11 cursor-pointer place-items-center rounded-xl border border-ink/10 text-2xl text-ink/45 hover:border-accent hover:bg-accent/10 hover:text-accent">&times;</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-5 lg:sticky lg:top-6">
                    <section class="overflow-hidden rounded-3xl bg-ink text-paper shadow-xl">
                        <div class="flex items-center justify-between border-b border-paper/10 px-5 py-4 sm:px-6">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-accent">Live preview</p>
                                <h2 class="mt-1 font-display text-xl font-semibold">What backers will see</h2>
                            </div>
                            <span class="font-mono text-xs text-paper/35">/draft</span>
                        </div>
                        <div class="p-4 sm:p-5">
                            <div class="relative aspect-[4/3] overflow-hidden rounded-2xl bg-paper/10">
                                <template x-if="previews.length">
                                    <img :src="previews[0].url" alt="Selected campaign preview" class="size-full object-cover" />
                                </template>
                                <template x-if="! previews.length">
                                    <div class="grid size-full place-items-center px-8 text-center">
                                        <div>
                                            <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-accent text-white">
                                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-6" stroke="currentColor" stroke-width="1.7">
                                                    <rect x="3" y="4" width="18" height="16" rx="2" />
                                                    <circle cx="8.5" cy="9" r="1.5" />
                                                    <path d="m21 15-4.5-4.5L7 20" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <p class="mt-3 text-sm font-semibold">Your product image will live here.</p>
                                            <p class="mt-1 text-xs leading-5 text-paper/45">Upload a strong first image to make the page feel real.</p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-5">
                                <p class="font-display text-2xl font-semibold" x-text="title || 'Your campaign title'"></p>
                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-paper/60" x-text="description || 'A short description will help people understand the idea at a glance.'"></p>
                                <div class="mt-5 flex items-end justify-between gap-4 border-t border-paper/10 pt-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.16em] text-paper/40">Backer price</p>
                                        <p class="mt-1 font-display text-2xl font-semibold"><span x-text="currency"></span> <span x-text="price || '0.00'"></span></p>
                                    </div>
                                    <span class="rounded-full bg-moss px-3 py-2 text-xs font-bold">Early support</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-ink/10 bg-white/75 p-5 shadow-sm sm:p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-moss">Before you publish</p>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-ink/60">
                            <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-accent"></span><span>Use a title people can repeat.</span></li>
                            <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-accent"></span><span>Show the product from its best angle.</span></li>
                            <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-accent"></span><span>Keep the promise and price easy to understand.</span></li>
                        </ul>
                    </section>

                    <button type="submit" wire:loading.attr="disabled" wire:target="campaign" class="flex min-h-14 w-full cursor-pointer items-center justify-center rounded-2xl bg-accent px-5 font-bold text-white shadow-[0_10px_28px_rgba(232,111,81,.25)] hover:bg-accent-dark disabled:cursor-wait disabled:opacity-60">
                        <span wire:loading.remove wire:target="campaign">Create campaign</span>
                        <span wire:loading wire:target="campaign">Saving campaign…</span>
                    </button>
                </aside>
            </div>
        </form>
    </div>
    <x-flash />
</x-dashboard-panel>

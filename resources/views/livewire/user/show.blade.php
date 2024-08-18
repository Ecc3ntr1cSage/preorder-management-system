@inject('carbon', 'Carbon\Carbon')
<div>
    <section class="py-12 sm:py-16">
        <div class="container px-4 mx-auto">
            <div class="grid grid-cols-1 gap-12 mt-8 lg:col-gap-12 xl:col-gap-16 lg:mt-12 lg:grid-cols-5 lg:gap-16">
                <div class="lg:col-span-3 lg:row-end-1">
                    <div class="lg:flex lg:items-start" x-data="{ image: {{ $campaign->images->first()->id }} }">
                        <div class="lg:order-2 lg:ml-5">
                            @foreach ($campaign->images as $image)
                                <div x-cloak x-show="image == {{ $image->id }}"
                                    class="max-w-xl overflow-hidden rounded-lg lg:shadow-lg shadow-black/70">
                                    <img class="object-cover w-full max-w-full max-h-screen"
                                        src="{{ asset('storage/campaign/' . $image->image) }}" alt="" />
                                </div>
                            @endforeach
                        </div>
                        <div class="w-full mt-2 lg:order-1 lg:w-32 lg:flex-shrink-0">
                            <div class="flex flex-row items-start gap-1 lg:gap-0 lg:flex-col">
                                @foreach ($campaign->images as $image)
                                    <button type="button" x-on:click="image = {{ $image->id }}"
                                        x-bind:class="image == {{ $image->id }} ? 'ring ring-indigo-500' : ''"
                                        class="h-20 mb-3 overflow-hidden text-center rounded-lg flex-0 aspect-square">
                                        <img class="object-cover w-full h-full"
                                            src="{{ asset('storage/campaign/' . $image->image) }}" class="max-h-96" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <form wire:submit.prevent="preorder" class="lg:col-span-2 lg:row-span-2 lg:row-end-2"
                    x-data="{ selectedVariations: {} }">
                    @csrf
                    <h1 class="-mt-8 text-2xl font-bold text-gray-900 capitalize lg:mt-0 sm:text-3xl">
                        {{ $campaign->title }}</h1>
                    @php
                        $endDate = $carbon::parse($campaign->end_date);
                        $today = $carbon::today();
                        $daysLeft = $endDate->diffInDays($today);
                    @endphp
                    <div class="flex items-center mt-5 mb-2">
                        <p
                            class="px-2 py-1 text-sm font-medium tracking-widest text-indigo-400 uppercase rounded-md bg-black/70 w-fit">
                            {{ $carbon::parse($campaign->start_date)->format('d F Y') }} -
                            {{ $carbon::parse($campaign->end_date)->format('d F Y') }}
                        </p>
                    </div>
                    <p
                        class="flex items-center gap-2 px-2 py-1 text-sm font-medium tracking-widest text-indigo-400 rounded-md bg-black/70 w-fit">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </span>
                        Ends in {{ $daysLeft }} {{ $daysLeft === 1 ? 'Day' : 'Days' }}
                    </p>
                    {{-- Check variations existence --}}
                    @if ($this->hasVariations($campaign->variations))
                        @foreach (json_decode($campaign->variations, true) as $variation)
                            <h2 class="mt-4 text-gray-700 capitalize">{{ $variation['name'] }}</h2>
                            <div class="flex flex-wrap items-center gap-1 my-2 select-none">
                                @foreach (explode(',', $variation['values']) as $value)
                                    <div class="min-w-16">
                                        <input type="radio"
                                            :id="'{{ $variation['name'] }}_' + '{{ $value }}'"
                                            value="{{ $value }}" class="hidden"
                                            wire:model="selectedVariations.{{ $variation['name'] }}"
                                            x-on:click="selectedVariations['{{ $variation['name'] }}'] = '{{ $value }}'" />
                                        <label :for="'{{ $variation['name'] }}_' + '{{ $value }}'"
                                            class="grid p-2 text-sm text-black uppercase transition border-2 rounded-md cursor-pointer place-items-center hover:scale-105"
                                            :class="selectedVariations['{{ $variation['name'] }}'] == '{{ $value }}' ?
                                                'border-indigo-600 text-indigo-600' :
                                                'border-black'"
                                            style="min-width: 4rem;">{{ $value }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error for="selectedVariations" />
                        @endforeach
                    @endif
                    <h2 class="mt-4 text-gray-900">Quantity</h2>
                    <div class="flex flex-row gap-4 my-3">
                        <div class="flex items-center justify-between px-4 rounded-md w-80 sm:w-auto md:px-0 ring-4 ring-gray-700"
                            x-data="{
                                quantity: @entangle('quantity'),
                                minus() {
                                    this.quantity = Math.max(1, parseInt(this.quantity) - 1);
                                },
                                plus() {
                                    this.quantity = parseInt(this.quantity) + 1;
                                }
                            }">
                            <svg x-on:click="minus()" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="m-3 text-gray-700 cursor-pointer w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                            </svg>
                            <input type="text" x-model="quantity"
                                class="w-16 text-lg text-center bg-transparent border-none" />
                            <svg x-on:click="plus()" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="m-3 text-gray-700 cursor-pointer w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center justify-between py-4 mt-10 space-y-4 border-t border-b border-indigo-500 sm:flex-row sm:space-y-0">
                        <div class="flex items-end">
                            <h1 class="text-3xl font-bold">RM{{ number_format($campaign->price / 100, 2) }}</h1>
                        </div>
                        <x-button type="submit" class="w-full sm:w-64" target="preorder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Preorder Now
                        </x-button>
                    </div>
                </form>
                <div class="lg:col-span-3" x-data="{ nav: 1 }">
                    <div class="border-b border-indigo-400">
                        <nav class="flex gap-4">
                            <button x-transition x-on:click="nav = 1" :class="nav == 1 ? 'border-b-2' : ''"
                                class="py-4 text-sm font-medium text-gray-900 border-indigo-500 hover:border-indigo-400 hover:text-gray-8s00">
                                Description </button>
                            <button x-transition x-on:click="nav = 2" :class="nav == 2 ? 'border-b-2' : ''"
                                class="py-4 text-sm font-medium text-gray-900 border-indigo-500 hover:border-indigo-400 hover:text-gray-800">
                                Details
                            </button>
                        </nav>
                    </div>
                    <div class="flow-root mt-8 sm:mt-8">
                        <p x-cloak x-show="nav == 1" class="mt-4">{{ $campaign->description }}</p>
                        <p x-cloak x-show="nav == 2" class="mt-4">{{ $campaign->details }}</p>
                    </div>
                </div>
            </div>
            <hr class="my-12 border-2 border-indigo-500" />
            <div class="max-w-2xl mx-auto mt-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 lg:text-2xl">Questions
                        ({{ $campaign->questions->count() }})</h2>
                </div>
                <form wire:submit.prevent="enquiry">
                    @csrf
                    <x-textarea class="w-full" rows="4" placeholder="Ask a question" wire:model="question" />
                    <x-input-error for="question" />
                    <button type="submit" wire:target="enquiry"
                        class="w-1/4 px-6 py-2 mt-2 text-sm text-white transition bg-gray-800 rounded-md hover:bg-indigo-500">Submit</button>
                </form>
                @foreach ($campaign->questions as $question)
                    <article class="px-6 py-4 mt-4 text-base rounded-lg bg-gray-700/10">
                        <p class="text-sm text-indigo-700"> <span class="text-xs text-gray-900/80">Posted on</span>
                            {{ $carbon::parse($question->created_at)->setTimeZone('Asia/Manila')->format('F d, g:i A') }}
                        </p>
                        <p class="mt-2 text-gray-900">{{ $question->question }}</p>
                        @if ($question->reply)
                            <div class="px-6 py-4 mt-2 rounded-lg bg-gray-700/20">
                                <p class="text-sm text-indigo-700">
                                    <span class="text-xs text-gray-900/90">Replied on</span>
                                    {{ $carbon::parse($question->reply->created_at)->setTimeZone('Asia/Manila')->format('F d, g:i A') }}
                                </p>
                                <p class="mt-2 text-gray-900">{{ $question->reply->reply }}</p>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    <x-flash />
</div>

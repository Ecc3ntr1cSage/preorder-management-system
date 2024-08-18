@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    <a href="{{ route('admin.campaign') }}" wire:navigate
        class="p-1 transition-all rounded-md hover:bg-zinc-600/60 hover:-translate-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
    </a>
    {{ $campaign->title }}
</x-slot>
<div>
    <x-admin-panel>
        <x-slot name="title">
            {{ __('Campaign Details') }}
        </x-slot>
        <div class="grid grid-cols-5 gap-4">
            @foreach ($campaign->images as $image)
                <img src="{{ asset('storage/campaign/' . $image->image) }}" class="w-full mb-2 rounded-md max-h-96" />
            @endforeach
        </div>
        <hr class="my-3 border border-black/20" />
        <div class="grid grid-cols-2 gap-2 text-gray-200">
            <div>
                <p>Title</p>
                <p class="px-2 py-1 my-1 rounded-md bg-black/40">{{ $campaign->title }}</p>
                <p>Description</p>
                <p class="px-2 py-1 my-1 rounded-md bg-black/40">{{ $campaign->description }}</p>
                <p>Details</p>
                <p class="px-2 py-1 my-1 rounded-md bg-black/40">{{ $campaign->details }}</p>
            </div>
            <div>
                <div class="grid grid-cols-3 gap-1">
                    <div>
                        <p>Price</p>
                        <p class="px-2 py-1 my-1 rounded-md bg-black/40">{{ $campaign->currency }}
                            {{ number_format($campaign->price / 100, 2) }}</p>
                    </div>
                    <div>
                        <p>Start Date</p>
                        <p class="px-2 py-1 my-1 rounded-md bg-black/40">
                            {{ $carbon::parse($campaign->start_date)->format('d F y') }}</p>
                    </div>
                    <div>
                        <p>End Date</p>
                        <p class="px-2 py-1 my-1 rounded-md bg-black/40">
                            {{ $carbon::parse($campaign->end_date)->format('d F y') }}</p>
                    </div>
                </div>
                <div>
                    <p>Shipping</p>
                    <div class="flex items-center gap-2">
                        @foreach (json_decode($campaign->shipping, true) as $key => $shipping)
                            <p class="px-2 py-1 my-1 capitalize rounded-md bg-black/40">{{ $key }}:
                                {{ $shipping }}</p>
                        @endforeach
                    </div>
                    <p>Variations</p>
                    @foreach (json_decode($campaign->variations, true) as $key => $variation)
                        <p class="px-2 py-1 my-1 capitalize rounded-md bg-black/40">{{ $variation['name'] }}:
                            {{ $variation['values'] }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </x-admin-panel>
    <div class="grid grid-cols-2">
        <x-admin-panel>
            <x-slot name="title">
                {{ __('Questions') }}
            </x-slot>
            @if ($campaign->questions->count() == 0)
                <p class="text-center text-gray-200">No questions have been received for this campaign at the moment.
                </p>
            @else
                @foreach ($campaign->questions as $question)
                    <article class="px-6 py-4 mt-4 border-2 rounded-lg border-white/30" x-data="{ id: '' }">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-indigo-400"> <span class="text-xs text-gray-300">Posted
                                    on</span>
                                {{ $carbon::parse($question->created_at)->setTimeZone('Asia/Manila')->format('F d, g:i A') }}
                            </p>
                            <button type="button" wire:click="$toggle('deleteQuestionModal')"
                                class="p-1 transition rounded-md cursor-pointer hover:bg-rose-500/80">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <x-confirmation-modal wire:model="deleteQuestionModal" maxWidth="lg">
                                <x-slot name="title">
                                    Delete Question
                                </x-slot>
                                <x-slot name="content">
                                    Are you sure you want to delete this question?
                                </x-slot>
                                <x-slot name="footer">
                                    <x-secondary-button wire:click="$toggle('deleteCampaignModal')"
                                        wire:loading.attr="disabled">
                                        Nevermind
                                    </x-secondary-button>
                                    <x-danger-button class="ml-2" wire:click="deleteQuestion({{ $question->id }})"
                                        wire:loading.attr="disabled">
                                        Delete
                                    </x-danger-button>
                                </x-slot>
                            </x-confirmation-modal>
                        </div>
                        <p class="mt-2 text-gray-100">{{ $question->question }}</p>
                        @if (!$question->reply)
                        @else
                            <div class="px-6 py-4 mt-2 rounded-lg bg-black/40">
                                <p class="text-sm text-indigo-400">
                                    <span class="text-xs text-gray-300">Replied on</span>
                                    {{ $carbon::parse($question->reply->created_at)->setTimeZone('Asia/Manila')->format('F d, g:i A') }}
                                </p>
                                <p class="mt-2 text-gray-100">{{ $question->reply->reply }}</p>
                            </div>
                        @endif
                    </article>
                @endforeach
            @endif
        </x-admin-panel>
        <x-admin-panel>
            <x-slot name="title">
                {{ __('Coupons') }}
            </x-slot>
            @if (!$campaign->coupon)
                <p class="text-center text-gray-200">No coupon code generated yet.</p>
            @else
                <div class="flex flex-col p-4 text-gray-200 rounded-md bg-black/40">
                    <x-label value="{{ __('Coupon Code') }}" />
                    <div class="relative border-2 border-indigo-500 rounded-md">
                        <p
                            class="px-4 py-2 text-xl font-bold tracking-wider text-transparent uppercase bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 w-fit">
                            {{ $campaign->coupon->code }}</p>
                        <button x-on:click="execCommand('copy')"
                            class="absolute px-4 py-2 bg-indigo-500 rounded top-0.5 right-0.5">
                            Copy
                        </button>
                    </div>
                    <div class="px-2 my-3">
                        <p class="font-medium text-indigo-400 ">
                            {{ $campaign->currency }}{{ $campaign->coupon->discount }} OFF</p>
                        <p>Limit for <span class="font-medium text-indigo-400">{{ $campaign->coupon->limit }}
                                People</span></p>
                        <p>Total usage <span class="font-medium text-indigo-400">{{ $campaign->coupon->usage }}
                                People </span></p>
                        <p>Valid until <span
                                class="font-medium text-indigo-400">{{ $carbon::parse($campaign->coupon->end_date)->format('d F, Y') }}</span>
                        </p>
                    </div>
                    <x-danger-button class="w-24"
                        wire:click="deleteCoupon({{ $campaign->coupon->id }})">Delete</x-danger-button>
                </div>
            @endif
        </x-admin-panel>
    </div>
    <x-flash />
</div>

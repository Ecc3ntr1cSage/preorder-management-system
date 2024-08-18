@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    <a href="{{ route('campaign.manage') }}" wire:navigate
        class="p-1 transition-all rounded-full hover:bg-zinc-600/60 hover:-translate-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
    </a>
    {{ $campaign->title }}

</x-slot>
<x-dashboard-panel>
    {{-- Campaign Images --}}
    <div class="grid grid-cols-5 gap-4 mt-2">
        @foreach ($campaign->images as $image)
            <div class="flex flex-col">
                <img src="{{ asset('storage/campaign/' . $image->image) }}" class="w-full mb-2 rounded-md max-h-96" />
                <button type="button" wire:click="deleteImage({{ $image->id }})"
                    class="w-full px-6 py-2 mt-auto text-sm text-white transition bg-gray-800 rounded-md flex-end hover:bg-indigo-500">Remove</button>
            </div>
        @endforeach
        @if (count($campaign->images) < 5)
            <div>
                <x-filepond wire:model="image" />
                <button type="button" wire:click="uploadImage" wire:target="uploadImage"
                    class="flex items-center justify-center w-full h-10 px-6 py-2 text-sm text-white transition bg-gray-800 rounded-md hover:bg-indigo-500">
                    <span wire:loading.remove wire:target="uploadImage">Upload</span>
                    <span wire:loading wire:target="uploadImage"> <svg class="w-6 h-6 text-white animate-spin"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="2">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0a12 12 0 00-12 12h2z">
                            </path>
                        </svg></span>
                </button>
            </div>
        @endif
    </div>
    {{-- Campaign Information --}}
    <div class="px-6 py-2">
        <form wire:submit.prevent="editCampaign" enctype="multipart/form-data">
            @csrf
            <hr class="my-3 border border-indigo-700/70" />
            <div class="grid grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <x-label value="{{ __('Title') }}" />
                    <x-input type="text" wire:model="title" class="w-full capitalize" disabled />
                    <x-label value="{{ __('Description') }}" class="mt-2" />
                    <x-textarea rows="4" wire:model="description" />
                    <x-input-error for="description" />
                    <x-label value="{{ __('Details') }}" class="mt-2" />
                    <x-textarea rows="4" wire:model="details" />
                    <x-input-error for="details" />
                </div>
                <div class="flex flex-col">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        <div>
                            <x-label value="{{ __('Price') }}" />
                            <x-input type="text" wire:model="price" class="w-full" disabled />
                        </div>
                        <div>
                            <x-label value="{{ __('Start Date') }}" />
                            <x-input type="date" wire:model="startDate" disabled />
                        </div>
                        <div>
                            <x-label value="{{ __('End Date') }}" />
                            <x-input type="date" wire:model="endDate" disabled />
                        </div>
                    </div>
                    {{-- Shipping Details --}}
                    <div class="mt-3 lg:mt-1" x-data="{ nav: Object.values(JSON.parse('{{ $campaign->shipping }}')).some(value => value !== '') ? 2 : 1 }">
                        <x-label value="{{ __('Shipping Options') }}" />
                        <div class="inline-flex p-1 gap-0.5 border-2 border-gray-400 rounded-md">
                            <button type="button" x-on:click="nav = 1" wire:click="resetShipping"
                                x-bind:class="nav == 1 ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-500/70 hover:text-white'"
                                class="inline-block px-4 py-2 text-sm text-gray-700 transition rounded">
                                Free Shipping
                            </button>
                            <button type="button" x-on:click="nav = 2"
                                x-bind:class="nav == 2 ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-500/70 hover:text-white'"
                                class="inline-block px-4 py-2 text-sm text-gray-700 transition rounded">
                                Enable Shipping
                            </button>
                        </div>
                        <div x-cloak x-transition x-show="nav == 2" class="grid grid-cols-1 gap-4 mt-2 lg:grid-cols-3">
                            <div>
                                <x-label value="{{ __('West Malaysia') }}" />
                                <x-input type="text" wire:model="shipping.west_malaysia" class="w-full" />
                                <x-input-error for="shipping.west_malaysia" />
                            </div>
                            <div>
                                <x-label value="{{ __('Sarawak') }}" />
                                <x-input type="text" wire:model="shipping.sarawak" class="w-full" />
                                <x-input-error for="shipping.sarawak" />
                            </div>
                            <div>
                                <x-label value="{{ __('Sabah') }}" />
                                <x-input type="text" wire:model="shipping.sabah" class="w-full" />
                                <x-input-error for="shipping.sabah" />
                            </div>
                        </div>
                    </div>
                    {{-- Variation Details --}}
                    <div class="mt-2" x-data="{ variations: @entangle('variations') }">
                        <x-label value="{{ __('Variations') }}" />
                        <div class="flex flex-col gap-2">
                            <template x-for="(variation, index) in variations" x-key="index">
                                <div class="flex items-stretch gap-1">
                                    <div class="w-1/4">
                                        <x-input class="w-full capitalize" type="text"
                                            x-model="variations[index].name" />
                                    </div>
                                    <div class="w-3/4">
                                        <x-input class="w-full" type="text" x-model="variations[index].values" />
                                    </div>
                                    <button type="button"
                                        class="block px-3 py-1 ml-2 text-xl transition rounded hover:bg-rose-500 hover:text-white"
                                        @click="variations.splice(index, 1)">
                                        &times
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="variations.push({name: '', values: ''});"
                                class="w-1/4 px-6 py-2 text-sm text-white transition bg-gray-800 rounded-md hover:bg-indigo-500">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-2 mt-8">
                {{-- Update Button --}}
                <x-button type="submit" class="w-64" target="editCampaign">Update</x-button>
                {{-- Question Modal Button --}}
                <button type="button" wire:click="$toggle('questionModal')"
                    class="p-1 transition rounded-md bg-emerald-400 hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                </button>
                {{-- Coupon Modal Button --}}
                <button type="button" wire:click="$toggle('couponModal')"
                    class="p-1 transition rounded-md bg-violet-500 hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </button>
                {{-- Delete Campaign Confirmation Button --}}
                @if ($campaign->orders->count() == 0)
                    <button type="button" wire:click="$toggle('deleteCampaignModal')"
                        class="p-1 transition rounded-md bg-rose-500 hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                @endif
            </div>
        </form>
    </div>
    {{-- Manage Questions --}}
    <x-dialog-modal wire:model="questionModal">
        <x-slot name="title">
            Questions ({{ $campaign->questions->count() }})
        </x-slot>
        <x-slot name="content">
            @if ($campaign->questions->count() == 0)
                <p class="text-center">No questions have been received for this campaign at the moment.</p>
            @else
                @foreach ($campaign->questions as $question)
                    <article class="px-6 py-4 mt-4 bg-gray-600 rounded-lg" x-data="{ id: '' }">
                        <p class="text-sm text-indigo-400"> <span class="text-xs text-gray-300">Posted
                                on</span>
                            {{ $carbon::parse($question->created_at)->setTimeZone('Asia/Manila')->format('F d, g:i A') }}
                        </p>
                        <p class="mt-2 text-gray-100">{{ $question->question }}</p>
                        @if (!$question->reply)
                            <div class="flex items-center my-4 space-x-4">
                                <button type="button" x-on:click="id = '{{ $question->id }}'"
                                    class="text-sm font-medium text-indigo-400 hover:underline">
                                    Reply
                                </button>
                            </div>
                            <form x-cloak wire:submit.prevent="answer({{ $question->id }})"
                                x-show="id == '{{ $question->id }}'" x-transition>
                                @csrf
                                <x-textarea class="w-full" rows="4" placeholder="Write a reply"
                                    wire:model="reply" />
                                <x-input-error for="reply" />
                                <button type="submit"
                                    class="w-1/4 px-6 py-2 mt-2 text-sm text-white transition bg-gray-800 rounded-md hover:bg-indigo-500">Submit</button>
                            </form>
                        @else
                            <div class="px-6 py-4 mt-2 bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-indigo-400">
                                        <span class="text-xs text-gray-300">Replied on</span>
                                        {{ $carbon::parse($question->reply->created_at)->setTimeZone('Asia/Manila')->format('F d, g:i A') }}
                                    </p>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 transition rounded-md cursor-pointer hover:text-rose-500"
                                        wire:click="deleteReply({{ $question->reply->id }})">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9.75L14.25 12m0 0l2.25 2.25M14.25 12l2.25-2.25M14.25 12L12 14.25m-2.58 4.92l-6.375-6.375a1.125 1.125 0 010-1.59L9.42 4.83c.211-.211.498-.33.796-.33H19.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-9.284c-.298 0-.585-.119-.796-.33z" />
                                    </svg>
                                </div>
                                <p class="mt-2 text-gray-100">{{ $question->reply->reply }}</p>
                            </div>
                        @endif
                    </article>
                @endforeach
            @endif
        </x-slot>
    </x-dialog-modal>
    {{-- Generate Coupon --}}
    <x-dialog-modal wire:model="couponModal" maxWidth="md">
        <x-slot name="title">
            @if (!$campaign->coupon)
                Generate Coupon Code
            @else
                Coupon Code
            @endif
        </x-slot>
        <x-slot name="content">
            @if (!$campaign->coupon)
                <form wire:submit.prevent="generateCoupon">
                    @csrf
                    <div class="flex flex-col">
                        <x-label value="{{ __('Coupon Code') }}" />
                        <div class="flex items-center gap-2">
                            <x-input type="text" wire:model="code" class="w-full uppercase" disabled />
                            <x-button-custom type="button" wire:click="generateCode">
                                Generate
                            </x-button-custom>
                        </div>
                        <x-input-error for="code" />
                        <div>
                            <x-label value="{{ __('Set Discount Value (Flat Rate)') }}" />
                            <x-input type="text" wire:model="discount" class="w-full" />
                            <x-input-error for="discount" />
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <x-label value="{{ __('Set Limit Person') }}" />
                                <x-input type="text" wire:model="limit" class="w-full" />
                                <x-input-error for="limit" />
                            </div>
                            <div>
                                <x-label value="{{ __('Set Expiry Date') }}" />
                                <x-input type="date" wire:model="expiry" class="w-full" :min="date('Y-m-d')" />
                                <x-input-error for="expiry" />
                            </div>
                        </div>
                        <x-button type="submit" class="mt-4" target="generateCoupon">Save
                            Coupon</x-button>
                    </div>
                </form>
            @else
                <div class="flex flex-col p-4 bg-gray-600 rounded-md">
                    <x-label value="{{ __('Coupon Code') }}" />
                    <div class="relative border-2 border-indigo-500 rounded-md">
                        <p
                            class="px-4 py-2 text-xl font-bold tracking-wider text-transparent uppercase bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 w-fit">
                            {{ $campaign->coupon->code }}</p>
                        <button class="absolute px-4 py-2 bg-indigo-500 rounded top-1 right-1">
                            Copy
                        </button>
                    </div>
                    <div class="px-2 my-3">
                        <p class="font-medium text-indigo-400">
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
        </x-slot>
    </x-dialog-modal>
    {{-- Delete Campaign --}}
    <x-confirmation-modal wire:model="deleteCampaignModal" maxWidth="lg">
        <x-slot name="title">
            Delete Campaign
        </x-slot>
        <x-slot name="content">
            Are you sure you want to delete your campaign?
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('deleteCampaignModal')" wire:loading.attr="disabled">
                Nevermind
            </x-secondary-button>
            <x-danger-button class="ml-2" wire:click="deleteCampaign({{ $campaign->id }})"
                wire:loading.attr="disabled">
                Delete
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
    <x-flash />
</x-dashboard-panel>

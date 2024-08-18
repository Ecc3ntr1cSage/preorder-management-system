<div class="relative w-full mx-auto">
    <div class="grid min-h-screen grid-cols-10">
        <div class="px-4 py-6 col-span-full sm:py-12 lg:col-span-6">
            <div class="w-full max-w-lg mx-auto">
                <a href="{{ route('campaign.show', $campaign->slug) }}" wire:navigate class="inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-8 h-8 p-1 mb-4 transition-all rounded-full hover:bg-indigo-500/40 hover:-translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <h1 class="relative text-2xl font-medium text-gray-700 sm:text-3xl">Secure Checkout<span
                        class="block w-10 h-1 mt-2 bg-indigo-500 sm:w-20"></span></h1>
                <form wire:submit.prevent="payment" class="flex flex-col mt-6">
                    @csrf
                    <x-label value="{{ __('Email') }}" />
                    <x-input type="text" wire:model="email" />
                    <x-input-error for="email" />
                    <x-label value="{{ __('Name') }}" />
                    <x-input type="text" wire:model="name" />
                    <x-input-error for="name" />
                    <x-label value="{{ __('Contact Number') }}" />
                    <x-input type="text" wire:model="phone" />
                    <x-input-error for="phone" />
                    <x-label value="{{ __('Address') }}" />
                    <x-input type="text" wire:model="address" />
                    <x-input-error for="address" />
                    <div class="flex gap-2">
                        <div>
                            <x-label value="{{ __('Postal Code') }}" />
                            <x-input type="text" wire:model="postcode" class="w-24 md:w-full" />
                            <x-input-error for="postcode" />
                        </div>
                        <div class="grow">
                            <x-label value="{{ __('State') }}" />
                            <select wire:change="calculateShipping($event.target.value)" wire:model="state"
                                class="w-full transition border-2 border-gray-400 rounded-md bg-white/20 focus:border-violet-500 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                                <option value="" selected>Choose a state</option>
                                @foreach (config('countries.malaysia.states') as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="state" />
                        </div>
                    </div>
                    <x-label value="{{ __('FPX Payment') }}" class="mt-8" />
                    <div class="grid w-full grid-cols-3 gap-3" x-data="{ radio: '' }">
                        @foreach (config('banks.banks') as $bankCode => $bank)
                            <div>
                                <input class="hidden" id="{{ $bankCode }}" type="radio" wire:model="bankCode"
                                    value="{{ $bankCode }}" x-on:click="radio = '{{ $bankCode }}'"
                                    x-model="radio">
                                <label
                                    class="flex flex-col p-4 transition bg-transparent border-2 rounded-md cursor-pointer"
                                    :class="radio == '{{ $bankCode }}' ? 'border-indigo-500' :
                                        'border-gray-400'"
                                    for="{{ $bankCode }}">
                                    <img src="{{ $bank['image'] }}" class="h-4 md:h-8" />
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <x-input-error for="bankCode" />
                    <p class="mt-10 text-sm font-semibold text-center text-gray-500">By placing this order you agree to
                        the
                        <a href="#" class="text-teal-400 underline whitespace-nowrap hover:text-teal-600">Terms
                            and
                            Conditions</a>
                    </p>
                    <x-button type="submit" target="payment" class="w-full mt-4">Place Order</x-button>
                </form>
            </div>
        </div>
        <div class="relative flex flex-col py-6 pl-8 pr-4 col-span-full sm:py-12 lg:col-span-4 lg:py-24">
            <div>
                <img src="asset/checkout.webp" alt="" class="absolute inset-0 object-cover w-full h-full" />
                <div
                    class="absolute inset-0 w-full h-full bg-gradient-to-tr from-purple-800/90 via-indigo-900/90 to-emerald-900/90">
                </div>
            </div>
            <div class="relative text-white">
                <div class="flex justify-between mx-1">
                    <div>
                        <p class="text-base font-semibold capitalize">{{ $campaign->title }}</p>
                        <p class="text-sm font-medium uppercase text-opacity-80">
                            {{ $preorder['variations'] }}</p>
                    </div>
                    <p class="font-semibold">RM {{ number_format($campaign->price / 100, 2) }}<span class="text-sm"> x
                            {{ $preorder['quantity'] }}</span></p>
                </div>
                <div class="my-5 h-0.5 w-full bg-white bg-opacity-30"></div>
                <div class="space-y-2" x-data="{ show: false }">
                    <p class="flex justify-between text-lg font-bold">
                        <span>Subtotal</span>
                        <span>RM {{ number_format($calculations['subtotal'] / 100, 2) }}</span>
                    </p>
                    <p class="flex justify-between text-sm font-medium">
                        <span>Shipping</span>
                        <span>
                            @if (empty(array_filter($this->shippingArray)))
                                Free Shipping
                            @else
                                RM {{ number_format($this->shipping, 2) }}
                            @endif
                        </span>
                    </p>
                    @if ($discount)
                        <p class="flex justify-between text-sm font-medium">
                            <span>Discount</span>
                            <span>
                                - RM {{ number_format($this->discount, 2) }}
                            </span>
                        </p>
                    @endif
                    @if (isset($campaign->coupon))
                        <button type="button" x-on:click="show = !show " class="text-xs text-green-400 underline">Have
                            a coupon</button>
                        <div x-cloak x-show="show" x-transition>
                            <div class="flex items-center gap-2">
                                <x-input type="text" placeholder="Coupon code" class="text-xs uppercase"
                                    wire:model="couponCode" />
                                <x-secondary-button class="text-xs" wire:click="applyCoupon">Apply</x-secondary-button>
                            </div>
                            <x-input-error for="couponCode" />
                        </div>
                    @endif
                </div>
                <div class="h-0.5 my-5 w-full bg-white bg-opacity-30"></div>
                <p class="flex justify-between text-lg font-bold">
                    <span>Total Price</span>
                    <span wire:loading.class="opacity-50">RM
                        {{ number_format($calculations['total'] / 100, 2) }}</span>
                </p>
            </div>
        </div>
    </div>
    <x-flash />
</div>

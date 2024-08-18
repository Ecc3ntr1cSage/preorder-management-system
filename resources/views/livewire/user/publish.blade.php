<x-slot name="header">
    {{ __('Start Pre-order Campaign') }}
</x-slot>
<x-dashboard-panel>
    <div class="lg:py-2 lg:px-6">
        <form wire:submit.prevent="campaign" enctype="multipart/form-data">
            @csrf
            <p class="text-sm text-gray-900">Upload up to 5 captivating images of your preorder product. <span
                    class="ml-1 text-xs text-blue-700">* You can remove and replace images as needed later. </span>
            </p>
            <div class="grid grid-cols-2 gap-4 mt-2 lg:gap-6 lg:grid-cols-5">
                <x-filepond wire:model="image" accept="image/*" />
                <x-filepond wire:model="image" accept="image/*" />
                <x-filepond wire:model="image" accept="image/*" />
                <x-filepond wire:model="image" accept="image/*" />
                <x-filepond wire:model="image" accept="image/*" />
            </div>
            {{-- <x-input-error for="image.*" /> --}}
            <hr class="my-3 border border-indigo-700/70" />
            <div class="grid gap-6 gird-cols-1 lg:grid-cols-2">
                <div class="flex flex-col">
                    <x-label value="{{ __('Title') }}" />
                    <x-input type="text" wire:model="title" class="capitalize" />
                    <x-input-error for="title" />
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
                            <div class="flex items-center gap-1">
                                <select wire:model="currency"
                                    class="transition border-2 border-gray-400 rounded-md bg-white/30 focus:border-violet-500 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                                    <option value="RM">RM</option>
                                </select>
                                <x-input type="text" wire:model="price" class="w-full" />
                            </div>
                            <x-input-error for="price" />
                        </div>
                        <div>
                            <x-label value="{{ __('Start Date') }}" />
                            <x-input type="date" wire:model="startDate" :min="date('Y-m-d')" class="w-full" />
                            <x-input-error for="startDate" />
                        </div>
                        <div>
                            <x-label value="{{ __('Duration') }}" />
                            <x-input type="date" wire:model="endDate" :min="date('Y-m-d')" class="w-full" />
                            <x-input-error for="endDate" />
                        </div>
                    </div>
                    <div class="mt-3 lg:mt-1" x-data="{ nav: 1 }">
                        <x-label value="{{ __('Shipping Options') }}" />
                        <div class="inline-flex p-1 gap-0.5 border border-gray-700 rounded-md">
                            <button type="button" x-on:click="nav = 1"
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

                    <div class="mt-2" x-data="{ variations: @entangle('variations') }">
                        <x-label value="{{ __('Variations') }}" />
                        <div class="flex flex-col gap-2">
                            <template x-for="(variation, index) in variations" x-key="index">
                                <div class="flex items-stretch gap-1">
                                    <div class="w-2/5 lg:w-1/4">
                                        <x-input class="w-full capitalize" type="text" x-model="variation.name"
                                            placeholder="eg: Size" />
                                    </div>
                                    <div class="w-3/5 lg:w-3/4">
                                        <x-input class="w-full" type="text" x-model="variation.values"
                                            placeholder="XS, S, M, L" />
                                    </div>
                                    <button
                                        class="block px-3 py-1 text-xl rounded-md hover:bg-rose-500 hover:text-white"
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
            <div class="flex items-center justify-center mt-8">
                <x-button type="submit" class="w-64" target="campaign">Create</x-button>
            </div>
        </form>
    </div>
    <x-flash />
</x-dashboard-panel>

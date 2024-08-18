<x-form-section submit="updateSocialLinks">
    <x-slot name="title">
        {{ __('Social Links') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s social links to display it in your campaign.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label value="{{ __('Instagram') }}" />
            <x-input type="text" class="block w-full mt-1" wire:model="links.instagram" autocomplete="links.instagram" />
            <x-input-error for="links.instagram" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label value="{{ __('Tiktok') }}" />
            <x-input type="text" class="block w-full mt-1" wire:model="links.tiktok" autocomplete="links.tiktok" />
            <x-input-error for="links.tiktok" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label value="{{ __('Facebook') }}" />
            <x-input type="text" class="block w-full mt-1" wire:model="links.facebook" autocomplete="links.facebook" />
            <x-input-error for="links.facebook" class="mt-2" />
        </div>
    </x-slot>
    
    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>
        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>

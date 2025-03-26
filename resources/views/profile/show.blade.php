<x-app-layout>
    <x-slot name="header">
        {{ __('Profile Settings') }}
    </x-slot>
    <div class="flex gap-2">
        <div class="sticky flex flex-col max-w-sm gap-3 p-4 text-sm text-white top-4 h-fit">
            <a class="hover:text-gray-100/70" href="#profile">Profile Information</a>
            <a class="hover:text-gray-100/70" href="#password">Update Password</a>
            <a class="hover:text-gray-100/70" href="#social">Social Links</a>
            <a class="hover:text-gray-100/70" href="#2fa">2FA</a>
            <a class="hover:text-gray-100/70" href="#session">Browser Sessions</a>
            <a class="hover:text-gray-100/70" href="#delete">Delete Account</a>
        </div>

        <div class="max-w-5xl py-10 mx-auto border-l-2 border-gray-50/20 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div id="profile" class="mb-6"></div>
                @livewire('profile.update-profile-information-form')
                <x-section-border />
            @endif
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div id="password" class="mb-6"></div>
                @livewire('profile.update-password-form')
                <x-section-border />
            @endif
            <div id="social" class="mb-6"></div>
            @livewire('profile.update-social-links')
            <x-section-border />
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div id="2fa" class="mb-6"></div>
                @livewire('profile.two-factor-authentication-form')
                <x-section-border />
            @endif
            <div id="session" class="mb-6"></div>
            @livewire('profile.logout-other-browser-sessions-form')
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />
                <div id="delete" class="mb-6"></div>
                @livewire('profile.delete-user-form')
            @endif
        </div>
    </div>
</x-app-layout>

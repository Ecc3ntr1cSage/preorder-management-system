<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <p class="font-display text-2xl font-semibold">pre<span class="text-accent">.</span>shop</p>
            <p class="mt-3 text-2xl font-semibold text-ink">Join the community</p>
            <p class="mt-1 text-sm text-ink/60">Create campaigns, back ideas, and keep everything together.</p>
        </x-slot>

        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div><x-label for="name" value="{{ __('Name') }}" /><x-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" /></div>
            <div><x-label for="email" value="{{ __('Email') }}" /><x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" /></div>
            <div><x-label for="password" value="{{ __('Password') }}" /><x-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" /></div>
            <div><x-label for="password_confirmation" value="{{ __('Confirm Password') }}" /><x-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" /></div>
            <x-button class="w-full">{{ __('Register') }}</x-button>
        </form>
        <p class="mt-5 text-center text-sm text-ink/60">Already a member? <a wire:navigate href="{{ route('login') }}" class="underline hover:text-accent">Log in</a></p>
    </x-authentication-card>
</x-guest-layout>

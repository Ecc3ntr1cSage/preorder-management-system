<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <p class="font-display text-2xl font-semibold">pre<span class="text-accent">.</span>shop</p>
            <p class="mt-3 text-2xl font-semibold text-ink">Welcome back</p>
            <p class="mt-1 text-sm text-ink/60">Sign in to manage your campaigns and orders.</p>
        </x-slot>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">{{ session('status') }}</div>
        @endif
        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div><x-label for="email" value="{{ __('Email') }}" /><x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" /></div>
            <div><x-label for="password" value="{{ __('Password') }}" /><x-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" /></div>
            <x-button type="submit" class="w-full">{{ __('Log in') }}</x-button>
        </form>
        <div class="mt-5 flex justify-between text-sm text-ink/60">
            @if (Route::has('password.request'))<a href="{{ route('password.request') }}" class="underline hover:text-accent">Forgot password?</a>@endif
            <a wire:navigate href="{{ route('register') }}" class="underline hover:text-accent">Join the community</a>
        </div>
    </x-authentication-card>
</x-guest-layout>

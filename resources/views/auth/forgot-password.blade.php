<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex items-center gap-4">
                <hr class="h-1 mx-auto my-4 border-0 rounded w-36 bg-gradient-to-r from-blue-500 to-indigo-500">
                <a href="/" wire:navigate>
                    <img src="{{ asset('asset/preorder.png') }}" alt="" class="w-12 h-12" />
                </a>
                <hr class="h-1 mx-auto my-4 border-0 rounded w-36 bg-gradient-to-r from-indigo-500 to-fuchsia-500">
            </div>
            <p class="mt-4 text-2xl font-bold tracking-widest text-gray-800 uppercase">Forgot Password</p>
        </x-slot>
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
            </div>
            <x-button class="w-full mt-4">
                {{ __('Email Password Reset Link') }}
            </x-button>
        </form>
    </x-authentication-card>
</x-guest-layout>

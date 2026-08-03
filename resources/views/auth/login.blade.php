@php
    $demoAccounts = [
        ['name' => 'Preshop Admin', 'email' => 'admin@preshop.test'],
        ['name' => 'Maya Tan', 'email' => 'maya@preshop.test'],
        ['name' => 'Irfan Rahman', 'email' => 'irfan@preshop.test'],
        ['name' => 'Nadia Lim', 'email' => 'nadia@preshop.test'],
        ['name' => 'Daniel Wong', 'email' => 'daniel@preshop.test'],
        ['name' => 'Sofia Lee', 'email' => 'sofia@preshop.test'],
    ];
@endphp

<div
    x-cloak
    x-show="loginOpen"
    x-on:keydown.escape.window="loginOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6"
>
    <div class="flex min-h-full items-center justify-center">
        <div
            x-show="loginOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-on:click="loginOpen = false"
            class="fixed inset-0 bg-ink/70 backdrop-blur-sm"
            aria-hidden="true"
        ></div>

        <section
            id="login-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="login-title"
            x-show="loginOpen"
            x-trap.inert.noscroll="loginOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="translate-y-4 opacity-0 sm:scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave-end="translate-y-4 opacity-0 sm:scale-95"
            x-data="{ email: @js(old('email', '')), password: '', selectedAccount: @js(old('email', '')), showPassword: false, submitting: false }"
            x-effect="if (loginOpen) $nextTick(() => $refs.email.focus())"
            class="surface-shadow relative grid w-full max-w-3xl overflow-hidden rounded-[2rem] bg-paper shadow-2xl sm:grid-cols-[.85fr_1.15fr]"
        >
            <button
                type="button"
                x-on:click="loginOpen = false"
                aria-label="Close login"
                class="absolute right-3 top-3 z-10 grid size-11 cursor-pointer place-items-center rounded-full bg-paper/90 text-ink hover:bg-white sm:right-4 sm:top-4"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5" stroke="currentColor" stroke-width="2">
                    <path d="M6 6l12 12M18 6 6 18" stroke-linecap="round" />
                </svg>
            </button>

            <div class="relative overflow-hidden bg-ink px-6 py-8 text-paper sm:px-8 sm:py-10">
                <div class="absolute -bottom-20 -right-20 size-56 rounded-full border-[36px] border-accent/70" aria-hidden="true"></div>
                <div class="relative flex h-full flex-col gap-7">
                    <div>
                        <p class="font-display text-xl font-semibold">pre<span class="text-accent">.</span>shop</p>
                        <h3 class="mt-8 font-display text-3xl font-semibold leading-tight">Use maker account</h3>
                        <p class="mt-2 text-sm leading-6 text-paper/60">Choose a demo profile. Every account uses <span class="font-semibold text-paper">password</span>.</p>
                    </div>
                    <div class="grid gap-2" aria-label="Demo maker accounts">
                        @foreach ($demoAccounts as $account)
                            <button
                                type="button"
                                x-on:click="email = '{{ $account['email'] }}'; password = 'password'; selectedAccount = email; $nextTick(() => $refs.email.focus())"
                                x-bind:aria-pressed="selectedAccount === '{{ $account['email'] }}'"
                                x-bind:class="selectedAccount === '{{ $account['email'] }}' ? 'border-accent bg-paper/15' : 'border-paper/15 bg-paper/5 hover:bg-paper/10'"
                                class="flex min-h-14 w-full cursor-pointer items-center justify-between gap-3 rounded-xl border px-4 py-2 text-left"
                            >
                                <span class="min-w-0">
                                    <span class="block text-sm font-bold text-paper">{{ $account['name'] }}</span>
                                    <span class="block truncate text-xs text-paper/55">{{ $account['email'] }}</span>
                                </span>
                                <span
                                    x-show="selectedAccount === '{{ $account['email'] }}'"
                                    class="shrink-0 text-xs font-bold uppercase tracking-[0.14em] text-accent"
                                >Selected</span>
                                <svg
                                    x-show="selectedAccount !== '{{ $account['email'] }}'"
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="size-4 shrink-0 text-paper/40"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="px-6 py-8 sm:px-9 sm:py-10">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-moss">Member workspace</p>
                <h2 id="login-title" class="mt-3 font-display text-3xl font-semibold tracking-tight">Welcome back</h2>
                <p class="mt-2 text-sm leading-6 text-ink/60">Sign in to manage campaigns, orders, and payouts.</p>

                @if (session('status'))
                    <p role="status" class="mt-5 rounded-xl bg-moss/10 px-4 py-3 text-sm font-medium text-moss">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('login') }}" x-on:submit="submitting = true" class="mt-7 space-y-5">
                    @csrf
                    <input type="hidden" name="login_modal" value="1">

                    <div>
                        <label for="login-email" class="block text-sm font-semibold text-ink">Email address</label>
                        <input
                            id="login-email"
                            x-ref="email"
                            x-model="email"
                            type="email"
                            name="email"
                            required
                            autocomplete="username"
                            @error('email') aria-invalid="true" aria-describedby="login-email-error" @enderror
                            class="mt-2 block min-h-12 w-full rounded-xl border border-ink/20 bg-white/65 px-4 text-base text-ink placeholder:text-ink/35 focus:border-accent focus:ring-accent"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p id="login-email-error" role="alert" class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <label for="login-password" class="block text-sm font-semibold text-ink">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-accent underline decoration-accent/30 underline-offset-4 hover:text-accent-dark">Forgot password?</a>
                            @endif
                        </div>
                        <div class="relative mt-2">
                            <input
                                id="login-password"
                                x-model="password"
                                x-bind:type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                @error('password') aria-invalid="true" aria-describedby="login-password-error" @enderror
                                class="block min-h-12 w-full rounded-xl border border-ink/20 bg-white/65 py-3 pl-4 pr-16 text-base text-ink focus:border-accent focus:ring-accent"
                            >
                            <button
                                type="button"
                                x-on:click="showPassword = ! showPassword"
                                x-text="showPassword ? 'Hide' : 'Show'"
                                class="absolute inset-y-0 right-1 min-w-12 cursor-pointer rounded-lg px-3 text-sm font-semibold text-ink/60 hover:text-accent"
                                aria-controls="login-password"
                            ></button>
                        </div>
                        @error('password')
                            <p id="login-password-error" role="alert" class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex min-h-11 cursor-pointer items-center gap-3 text-sm text-ink/65">
                        <input type="checkbox" name="remember" class="size-4 rounded border-ink/30 text-accent focus:ring-accent">
                        <span>Keep me signed in</span>
                    </label>

                    <button
                        type="submit"
                        x-bind:disabled="submitting"
                        class="flex min-h-12 w-full cursor-pointer items-center justify-center rounded-xl bg-accent px-5 font-bold text-white shadow-[0_8px_24px_rgba(232,111,81,.25)] hover:bg-accent-dark disabled:cursor-wait disabled:opacity-60"
                    >
                        <span x-show="! submitting">Enter workspace</span>
                        <span x-cloak x-show="submitting">Signing in…</span>
                    </button>
                </form>

                <p class="mt-5 text-center text-xs leading-5 text-ink/45">Demo project · Existing accounts only</p>
            </div>
        </section>
    </div>
</div>

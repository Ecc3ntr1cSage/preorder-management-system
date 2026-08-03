<x-app-layout>
    @php
        $operator = auth()->user();
        $campaignCount = $operator->campaigns()->count();
        $visibleSections = 3 + (Laravel\Fortify\Features::canManageTwoFactorAuthentication() ? 1 : 0) + (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures() ? 1 : 0);
    @endphp

    <div class="relative overflow-clip bg-paper text-ink">
        <div class="grain pointer-events-none fixed inset-0 z-40 opacity-40"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[34rem] bg-radial-mesh opacity-80"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-10 lg:py-16">
            <header class="border-b border-ink/10 pb-10 lg:pb-14">
                <div class="flex flex-wrap items-center justify-between gap-4 text-xs font-bold uppercase tracking-[0.2em] text-ink/45">
                    <p><span class="text-accent">+</span> Account / profile</p>
                    <p class="rounded-full bg-white/60 px-3 py-1.5 ring-1 ring-ink/10">{{ $operator->is_admin ? 'Admin workspace' : 'Maker workspace' }}</p>
                </div>

                <div class="mt-10 grid items-end gap-10 lg:grid-cols-[1.1fr_.9fr] lg:gap-20">
                    <div>
                        <p class="inline-flex rounded-full bg-accent/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.22em] text-accent">Identity control</p>
                        <h1 class="mt-5 max-w-3xl font-display text-5xl font-semibold leading-[0.95] tracking-[-0.06em] sm:text-7xl lg:text-8xl">Your studio, made personal.</h1>
                        <p class="mt-6 max-w-xl text-base leading-7 text-ink/60 sm:text-lg">Keep your public identity, security, and workspace access in one calm place.</p>
                    </div>

                    <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                        <div class="rounded-[1.6rem] bg-ink p-6 text-paper shadow-[inset_0_1px_1px_rgba(255,255,255,.12)] sm:p-8">
                            <div class="flex items-start justify-between gap-5">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent">Active profile</p>
                                    <h2 class="mt-3 font-display text-2xl font-semibold">{{ $operator->name }}</h2>
                                    <p class="mt-1 break-all text-sm text-paper/55">{{ $operator->email }}</p>
                                </div>
                                <div class="grid size-14 shrink-0 place-items-center overflow-hidden rounded-2xl bg-paper/10 ring-1 ring-paper/15">
                                    <img src="{{ $operator->profile_photo_url }}" alt="{{ $operator->name }}" class="size-full object-cover">
                                </div>
                            </div>
                            <div class="mt-8 grid grid-cols-3 gap-3 border-t border-paper/15 pt-5">
                                <div><p class="text-[10px] uppercase tracking-[0.15em] text-paper/45">Since</p><p class="mt-2 font-display text-lg">{{ $operator->created_at->format('Y') }}</p></div>
                                <div><p class="text-[10px] uppercase tracking-[0.15em] text-paper/45">Campaigns</p><p class="mt-2 font-display text-lg">{{ $campaignCount }}</p></div>
                                <div><p class="text-[10px] uppercase tracking-[0.15em] text-paper/45">Status</p><p class="mt-2 font-display text-lg text-accent">Active</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="grid items-start gap-8 pt-8 lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-12 lg:pt-12">
                <aside class="lg:sticky lg:top-6">
                    <div class="rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                        <div class="rounded-[1.6rem] bg-white/75 p-5 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-6">
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-moss">Control room</p>
                            <nav class="mt-5 flex gap-2 overflow-x-auto pb-1 lg:flex-col" aria-label="Profile sections">
                                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                                    <a href="#unit-01" class="group flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink/60 hover:bg-ink hover:text-paper"><span class="font-mono text-xs text-accent">01</span> Identity</a>
                                @endif
                                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                    <a href="#unit-02" class="group flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink/60 hover:bg-ink hover:text-paper"><span class="font-mono text-xs text-accent">02</span> Password</a>
                                @endif
                                @if (! $operator->is_admin)
                                    <a href="#unit-03" class="group flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink/60 hover:bg-ink hover:text-paper"><span class="font-mono text-xs text-accent">03</span> Social links</a>
                                @endif
                                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                    <a href="#unit-04" class="group flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink/60 hover:bg-ink hover:text-paper"><span class="font-mono text-xs text-accent">04</span> Two factor</a>
                                @endif
                                <a href="#unit-05" class="group flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink/60 hover:bg-ink hover:text-paper"><span class="font-mono text-xs text-accent">05</span> Sessions</a>
                                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                                    <a href="#unit-06" class="group flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink/60 hover:bg-ink hover:text-paper"><span class="font-mono text-xs text-accent">06</span> Delete</a>
                                @endif
                            </nav>
                            <div class="mt-6 border-t border-ink/10 pt-5 text-xs leading-5 text-ink/45">{{ $visibleSections }} areas to keep your account ready.</div>
                        </div>
                    </div>
                </aside>

                <main class="space-y-6">
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        <section id="unit-01" class="scroll-mt-8 rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                            <div class="rounded-[1.6rem] bg-white/80 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-8 lg:p-10">
                                <div class="mb-8 flex items-start justify-between gap-5 border-b border-ink/10 pb-6"><div><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent">01 / Identity</p><h2 class="mt-2 font-display text-3xl font-semibold">Make your presence recognizable.</h2></div><span class="font-mono text-xs text-ink/30">PROFILE</span></div>
                                @livewire('profile.update-profile-information-form')
                            </div>
                        </section>
                    @endif

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        <section id="unit-02" class="scroll-mt-8 rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                            <div class="rounded-[1.6rem] bg-white/80 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-8 lg:p-10">
                                <div class="mb-8 flex items-start justify-between gap-5 border-b border-ink/10 pb-6"><div><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-moss">02 / Security</p><h2 class="mt-2 font-display text-3xl font-semibold">Keep the keys current.</h2></div><span class="font-mono text-xs text-ink/30">ACCESS</span></div>
                                @livewire('profile.update-password-form')
                            </div>
                        </section>
                    @endif

                    @if (! $operator->is_admin)
                        <section id="unit-03" class="scroll-mt-8 rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                            <div class="rounded-[1.6rem] bg-white/80 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-8 lg:p-10">
                                <div class="mb-8 flex items-start justify-between gap-5 border-b border-ink/10 pb-6"><div><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-moss">03 / Attribution</p><h2 class="mt-2 font-display text-3xl font-semibold">Let people find your work.</h2></div><span class="font-mono text-xs text-ink/30">SOCIAL</span></div>
                                @livewire('profile.update-social-links')
                            </div>
                        </section>
                    @endif

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <section id="unit-04" class="scroll-mt-8 rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                            <div class="rounded-[1.6rem] bg-white/80 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-8 lg:p-10">
                                <div class="mb-8 flex items-start justify-between gap-5 border-b border-ink/10 pb-6"><div><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-moss">04 / Protection</p><h2 class="mt-2 font-display text-3xl font-semibold">Add another layer of calm.</h2></div><span class="font-mono text-xs text-ink/30">2FA</span></div>
                                @livewire('profile.two-factor-authentication-form')
                            </div>
                        </section>
                    @endif

                    <section id="unit-05" class="scroll-mt-8 rounded-[2rem] bg-ink/5 p-1.5 ring-1 ring-ink/10">
                        <div class="rounded-[1.6rem] bg-white/80 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-8 lg:p-10">
                            <div class="mb-8 flex items-start justify-between gap-5 border-b border-ink/10 pb-6"><div><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-moss">05 / Sessions</p><h2 class="mt-2 font-display text-3xl font-semibold">Know where you’re signed in.</h2></div><span class="font-mono text-xs text-ink/30">DEVICES</span></div>
                            @livewire('profile.logout-other-browser-sessions-form')
                        </div>
                    </section>

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <section id="unit-06" class="scroll-mt-8 rounded-[2rem] bg-accent/10 p-1.5 ring-1 ring-accent/20">
                            <div class="rounded-[1.6rem] bg-white/80 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,.8)] sm:p-8 lg:p-10">
                                <div class="mb-8 flex items-start justify-between gap-5 border-b border-accent/20 pb-6"><div><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent">06 / Irreversible</p><h2 class="mt-2 font-display text-3xl font-semibold">Leave only when you mean it.</h2></div><span class="font-mono text-xs text-accent/60">DELETE</span></div>
                                @livewire('profile.delete-user-form')
                            </div>
                        </section>
                    @endif
                </main>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    @php
        $operator = auth()->user();
    @endphp

    <div class="swiss-7a relative min-h-screen overflow-x-clip bg-[#f4f4f0] font-sans text-[#0b0b0b] selection:bg-[#e61919] selection:text-[#f4f4f0]">
        <div class="grain pointer-events-none fixed inset-0 z-40"></div>

        <div class="mx-auto max-w-[1440px] border-x border-[#0b0b0b]">
            <header class="border-b-2 border-[#0b0b0b]">
                <div class="swiss-mono flex items-center justify-between gap-4 border-b border-[#0b0b0b] px-4 py-2.5 text-[10px] uppercase tracking-[0.2em] sm:px-8">
                    <p class="font-bold">pre.shop<span class="text-[#e61919]">.</span></p>
                    <p class="hidden sm:block"><samp>[ operator record — form 9-p ]</samp></p>
                    <p class="text-right">rev 2.6 / ©2026</p>
                </div>

                <div class="grid gap-px bg-[#0b0b0b] lg:grid-cols-12">
                    <div class="relative bg-[#f4f4f0] p-5 sm:p-8 lg:col-span-9 lg:p-12">
                        <div class="swiss-mono flex flex-wrap items-center gap-x-4 gap-y-1 text-[10px] uppercase tracking-[0.22em]">
                            <span class="text-[#e61919]">+</span>
                            <span>unit / identity</span>
                            <span class="text-[#e61919]">+</span>
                            <span>status : <span class="text-[#e61919]">active</span></span>
                            <span class="text-[#e61919]">+</span>
                            <span>role : {{ $operator->is_admin ? 'admin' : 'maker' }}</span>
                        </div>
                        <h1 class="swiss-display mt-6 font-black uppercase leading-[0.85] tracking-[-0.05em] text-[clamp(2.75rem,8vw,8.5rem)]">
                            Operator
                            <span class="block text-[#e61919]">Profile</span>
                        </h1>
                        <p class="swiss-mono mt-6 max-w-md text-[11px] uppercase leading-relaxed tracking-[0.15em] text-[#0b0b0b]/60">
                            {{ $operator->name }} / {{ $operator->email }}
                        </p>
                    </div>

                    <aside class="swiss-mono hidden flex-col justify-between gap-6 bg-[#f4f4f0] p-6 text-[10px] uppercase tracking-[0.15em] lg:col-span-3 lg:flex lg:border-l">
                        <dl class="space-y-3">
                            <div class="flex justify-between gap-4 border-b border-[#0b0b0b]/20 pb-2">
                                <dt class="text-[#0b0b0b]/50">member since</dt>
                                <dd><data value="{{ $operator->created_at->format('Y-m-d') }}">{{ $operator->created_at->format('d.m.Y') }}</data></dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-[#0b0b0b]/20 pb-2">
                                <dt class="text-[#0b0b0b]/50">email status</dt>
                                <dd class="text-[#e61919]">{{ $operator->hasVerifiedEmail() ? 'verified' : 'unverified' }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-[#0b0b0b]/20 pb-2">
                                <dt class="text-[#0b0b0b]/50">campaigns</dt>
                                <dd><data value="{{ $operator->campaigns()->count() }}">{{ $operator->campaigns()->count() }}</data></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-[#0b0b0b]/50">access level</dt>
                                <dd><kbd class="border border-[#0b0b0b] px-1.5 py-0.5">{{ $operator->is_admin ? 'admin' : 'std' }}</kbd></dd>
                            </div>
                        </dl>
                        <div>
                            <p class="mb-2 text-[#0b0b0b]/50">id serial</p>
                            <div class="swiss-barcode h-10 w-full opacity-90"></div>
                        </div>
                    </aside>
                </div>

                <div class="swiss-stripes h-3 w-full"></div>
            </header>

            <div class="grid gap-px bg-[#0b0b0b] lg:grid-cols-12">
                <aside class="swiss-mono bg-[#f4f4f0] p-5 sm:p-8 lg:col-span-3 lg:p-10">
                    <p class="text-[10px] uppercase tracking-[0.22em] text-[#0b0b0b]/50">maintenance index</p>
                    <nav class="mt-5 flex flex-col divide-y divide-[#0b0b0b]/15 border-y border-[#0b0b0b]/15">
                        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                            <a href="#unit-01" class="group flex items-center gap-3 px-2 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-[#0b0b0b] hover:text-[#f4f4f0]">
                                <span class="text-[#e61919]">+</span> unit 01 <span class="ml-auto text-[#0b0b0b]/40 group-hover:text-[#f4f4f0]/60">identity</span>
                            </a>
                        @endif
                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                            <a href="#unit-02" class="group flex items-center gap-3 px-2 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-[#0b0b0b] hover:text-[#f4f4f0]">
                                <span class="text-[#e61919]">+</span> unit 02 <span class="ml-auto text-[#0b0b0b]/40 group-hover:text-[#f4f4f0]/60">credentials</span>
                            </a>
                        @endif
                        @if (! $operator->is_admin)
                            <a href="#unit-03" class="group flex items-center gap-3 px-2 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-[#0b0b0b] hover:text-[#f4f4f0]">
                                <span class="text-[#e61919]">+</span> unit 03 <span class="ml-auto text-[#0b0b0b]/40 group-hover:text-[#f4f4f0]/60">social links</span>
                            </a>
                        @endif
                        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                            <a href="#unit-04" class="group flex items-center gap-3 px-2 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-[#0b0b0b] hover:text-[#f4f4f0]">
                                <span class="text-[#e61919]">+</span> unit 04 <span class="ml-auto text-[#0b0b0b]/40 group-hover:text-[#f4f4f0]/60">two factor</span>
                            </a>
                        @endif
                        <a href="#unit-05" class="group flex items-center gap-3 px-2 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-[#0b0b0b] hover:text-[#f4f4f0]">
                            <span class="text-[#e61919]">+</span> unit 05 <span class="ml-auto text-[#0b0b0b]/40 group-hover:text-[#f4f4f0]/60">sessions</span>
                        </a>
                        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                            <a href="#unit-06" class="group flex items-center gap-3 px-2 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-[#0b0b0b] hover:text-[#f4f4f0]">
                                <span class="text-[#e61919]">+</span> unit 06 <span class="ml-auto text-[#0b0b0b]/40 group-hover:text-[#f4f4f0]/60">termination</span>
                            </a>
                        @endif
                    </nav>
                    <div class="mt-8">
                        <p class="mb-2 text-[#0b0b0b]/50">sections : 06</p>
                        <div class="swiss-barcode h-8 w-full opacity-80"></div>
                    </div>
                </aside>

                <main class="bg-[#f4f4f0] lg:col-span-9">
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        <section id="unit-01" class="scroll-mt-6 border-b-2 border-[#0b0b0b]">
                            <div class="swiss-mono flex items-center gap-3 bg-[#0b0b0b] px-4 py-2 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0] sm:px-8">
                                <span class="text-[#e61919]">+</span> unit 01 / identity
                                <span class="ml-auto hidden sm:block">[ profile information ]</span>
                            </div>
                            <div class="p-5 sm:p-8 lg:p-10">
                                @livewire('profile.update-profile-information-form')
                            </div>
                        </section>
                    @endif

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        <section id="unit-02" class="scroll-mt-6 border-b-2 border-[#0b0b0b]">
                            <div class="swiss-mono flex items-center gap-3 bg-[#0b0b0b] px-4 py-2 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0] sm:px-8">
                                <span class="text-[#e61919]">+</span> unit 02 / credentials
                                <span class="ml-auto hidden sm:block">[ update password ]</span>
                            </div>
                            <div class="p-5 sm:p-8 lg:p-10">
                                @livewire('profile.update-password-form')
                            </div>
                        </section>
                    @endif

                    @if (! $operator->is_admin)
                        <section id="unit-03" class="scroll-mt-6 border-b-2 border-[#0b0b0b]">
                            <div class="swiss-mono flex items-center gap-3 bg-[#0b0b0b] px-4 py-2 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0] sm:px-8">
                                <span class="text-[#e61919]">+</span> unit 03 / social links
                                <span class="ml-auto hidden sm:block">[ campaign attribution ]</span>
                            </div>
                            <div class="p-5 sm:p-8 lg:p-10">
                                @livewire('profile.update-social-links')
                            </div>
                        </section>
                    @endif

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <section id="unit-04" class="scroll-mt-6 border-b-2 border-[#0b0b0b]">
                            <div class="swiss-mono flex items-center gap-3 bg-[#0b0b0b] px-4 py-2 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0] sm:px-8">
                                <span class="text-[#e61919]">+</span> unit 04 / two factor
                                <span class="ml-auto hidden sm:block">[ authentication ]</span>
                            </div>
                            <div class="p-5 sm:p-8 lg:p-10">
                                @livewire('profile.two-factor-authentication-form')
                            </div>
                        </section>
                    @endif

                    <section id="unit-05" class="scroll-mt-6 border-b-2 border-[#0b0b0b]">
                        <div class="swiss-mono flex items-center gap-3 bg-[#0b0b0b] px-4 py-2 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0] sm:px-8">
                            <span class="text-[#e61919]">+</span> unit 05 / sessions
                            <span class="ml-auto hidden sm:block">[ browser sessions ]</span>
                        </div>
                        <div class="p-5 sm:p-8 lg:p-10">
                            @livewire('profile.logout-other-browser-sessions-form')
                        </div>
                    </section>

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <section id="unit-06" class="scroll-mt-6">
                            <div class="swiss-mono flex items-center gap-3 bg-[#0b0b0b] px-4 py-2 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0] sm:px-8">
                                <span class="text-[#e61919]">+</span> unit 06 / termination
                                <span class="ml-auto hidden sm:block">[ delete account ]</span>
                            </div>
                            <div class="p-5 sm:p-8 lg:p-10">
                                @livewire('profile.delete-user-form')
                            </div>
                        </section>
                    @endif
                </main>
            </div>

            <footer class="swiss-mono flex flex-wrap items-center justify-between gap-4 border-t-2 border-[#0b0b0b] px-4 py-4 text-[10px] uppercase tracking-[0.2em] sm:px-8">
                <p>pre.shop · make something people can get behind</p>
                <p><span class="text-[#e61919]">+</span> form 9-p rev 2.6 <span class="text-[#e61919]">+</span> ©2026 pre.shop™</p>
                <div class="swiss-barcode h-6 w-24 opacity-80"></div>
            </footer>
        </div>
    </div>
</x-app-layout>

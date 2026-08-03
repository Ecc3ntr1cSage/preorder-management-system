<div>
    <p class="swiss-mono max-w-xl text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/65">
        {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
    </p>

    @if (count($this->sessions) > 0)
        <div class="mt-6 divide-y divide-[#0b0b0b]/15 border-y border-[#0b0b0b]/15">
            @foreach ($this->sessions as $session)
                <div class="flex items-center gap-4 py-3">
                    <div class="swiss-mono flex h-10 w-10 shrink-0 items-center justify-center border border-[#0b0b0b]/40 text-[10px] tracking-[0.15em]">
                        {{ $session->agent->isDesktop() ? 'PC' : 'MOB' }}
                    </div>
                    <div class="swiss-mono">
                        <div class="text-[11px] uppercase tracking-[0.12em] text-[#0b0b0b]">
                            {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} — {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                        </div>
                        <div class="mt-0.5 text-[10px] uppercase tracking-[0.12em] text-[#0b0b0b]/55">
                            {{ $session->ip_address }},
                            @if ($session->is_current_device)
                                <span class="text-[#e61919]">{{ __('This device') }}</span>
                            @else
                                {{ __('Last active') }} {{ $session->last_active }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-6 flex items-center gap-4">
        <button type="button" class="swiss-btn--primary" wire:click="confirmLogout" wire:loading.attr="disabled">
            Log Out Other Sessions
        </button>

        <div x-data="{ shown: false, timeout: null }"
            x-init="@this.on('loggedOut', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
            x-show.transition.out.opacity.duration.1500ms="shown"
            x-transition:leave.opacity.duration.1500ms
            style="display: none;"
            class="swiss-mono text-[10px] uppercase tracking-[0.2em] text-[#e61919]">
            <samp>[ ok ] done</samp>
        </div>
    </div>

    <!-- Log Out Other Devices Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingLogout').live }" x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false" x-show="show" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
        <div x-show="show" class="fixed inset-0" x-on:click="show = false">
            <div class="absolute inset-0 bg-[#0b0b0b]/80"></div>
        </div>
        <div x-show="show" class="relative mx-auto w-full max-w-2xl border-2 border-[#0b0b0b] bg-[#f4f4f0]">
            <div class="swiss-mono flex items-center justify-between gap-4 bg-[#0b0b0b] px-4 py-3 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0]">
                <span><span class="text-[#e61919]">+</span> confirm / log out sessions</span>
                <button type="button" class="text-[#e61919] hover:text-[#f4f4f0]" x-on:click="show = false">&times;</button>
            </div>
            <div class="p-5 sm:p-8">
                <p class="swiss-mono max-w-xl text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/70">
                    {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}
                </p>
                <div class="mt-5" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" class="swiss-field block w-3/4"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="logoutOtherBrowserSessions" />
                    @error('password')
                        <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-[#0b0b0b] px-4 py-4 sm:px-8">
                <button type="button" class="swiss-btn" wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="swiss-btn--primary"
                            wire:click="logoutOtherBrowserSessions"
                            wire:loading.attr="disabled">
                    Log Out Sessions
                </button>
            </div>
        </div>
    </div>
</div>

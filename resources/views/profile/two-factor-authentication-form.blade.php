<div>
    <h3 class="swiss-mono text-[11px] uppercase tracking-[0.2em]">
        @if ($this->enabled)
            @if ($showingConfirmation)
                <span class="text-[#e61919]">[ + ]</span> {{ __('Finish enabling two factor authentication.') }}
            @else
                <span class="text-[#e61919]">[ + ]</span> {{ __('You have enabled two factor authentication.') }}
            @endif
        @else
            <span class="text-[#0b0b0b]/50">[ – ]</span> {{ __('You have not enabled two factor authentication.') }}
        @endif
    </h3>

    <p class="swiss-mono mt-4 max-w-xl text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/65">
        {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
    </p>

    @if ($this->enabled)
        @if ($showingQrCode)
            <div class="mt-6 max-w-xl">
                <p class="swiss-mono text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/65">
                    @if ($showingConfirmation)
                        {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                    @else
                        {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                    @endif
                </p>

                <div class="mt-4 inline-block border-2 border-[#0b0b0b] bg-white p-2">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <p class="swiss-mono mt-4 text-[10px] uppercase tracking-[0.15em] text-[#0b0b0b]/70">
                    setup key : <samp class="break-all text-[#e61919]">{{ decrypt($this->user->two_factor_secret) }}</samp>
                </p>

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <label for="code" class="swiss-label">confirmation code</label>
                        <input id="code" type="text" name="code" class="swiss-field block w-1/2" inputmode="numeric"
                            autofocus autocomplete="one-time-code"
                            wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />
                        @error('code')
                            <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        @endif

        @if ($showingRecoveryCodes)
            <div class="mt-6 max-w-xl">
                <p class="swiss-mono text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/65">
                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>

                <div class="swiss-mono mt-4 grid gap-1 border-2 border-[#0b0b0b] bg-white px-4 py-4 text-[11px] tracking-[0.08em]">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div><samp>{{ $code }}</samp></div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <div class="mt-6 flex flex-wrap items-center gap-3">
        @if (! $this->enabled)
            <span wire:then="enableTwoFactorAuthentication" x-data x-ref="span"
                x-on:click="$wire.startConfirmingPassword('{{ md5('enableTwoFactorAuthentication') }}')"
                x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ md5('enableTwoFactorAuthentication') }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
                <button type="button" class="swiss-btn--primary" wire:loading.attr="disabled">{{ __('Enable') }}</button>
            </span>
        @else
            @if ($showingRecoveryCodes)
                <span wire:then="regenerateRecoveryCodes" x-data x-ref="span"
                    x-on:click="$wire.startConfirmingPassword('{{ md5('regenerateRecoveryCodes') }}')"
                    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ md5('regenerateRecoveryCodes') }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
                    <button type="button" class="swiss-btn">{{ __('Regenerate Recovery Codes') }}</button>
                </span>
            @elseif ($showingConfirmation)
                <span wire:then="confirmTwoFactorAuthentication" x-data x-ref="span"
                    x-on:click="$wire.startConfirmingPassword('{{ md5('confirmTwoFactorAuthentication') }}')"
                    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ md5('confirmTwoFactorAuthentication') }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
                    <button type="button" class="swiss-btn--primary mr-3" wire:loading.attr="disabled">{{ __('Confirm') }}</button>
                </span>
            @else
                <span wire:then="showRecoveryCodes" x-data x-ref="span"
                    x-on:click="$wire.startConfirmingPassword('{{ md5('showRecoveryCodes') }}')"
                    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ md5('showRecoveryCodes') }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
                    <button type="button" class="swiss-btn">{{ __('Show Recovery Codes') }}</button>
                </span>
            @endif

            @if ($showingConfirmation)
                <span wire:then="disableTwoFactorAuthentication" x-data x-ref="span"
                    x-on:click="$wire.startConfirmingPassword('{{ md5('disableTwoFactorAuthentication') }}')"
                    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ md5('disableTwoFactorAuthentication') }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
                    <button type="button" class="swiss-btn" wire:loading.attr="disabled">{{ __('Cancel') }}</button>
                </span>
            @else
                <span wire:then="disableTwoFactorAuthentication" x-data x-ref="span"
                    x-on:click="$wire.startConfirmingPassword('{{ md5('disableTwoFactorAuthentication') }}')"
                    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ md5('disableTwoFactorAuthentication') }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);">
                    <button type="button" class="swiss-btn--danger" wire:loading.attr="disabled">{{ __('Disable') }}</button>
                </span>
            @endif
        @endif
    </div>

    <!-- Confirm Password Modal -->
    <div x-data="{ show: @entangle('confirmingPassword').live }" x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false" x-show="show" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
        <div x-show="show" class="fixed inset-0" x-on:click="show = false">
            <div class="absolute inset-0 bg-[#0b0b0b]/80"></div>
        </div>
        <div x-show="show" class="relative mx-auto w-full max-w-2xl border-2 border-[#0b0b0b] bg-[#f4f4f0]">
            <div class="swiss-mono flex items-center justify-between gap-4 bg-[#0b0b0b] px-4 py-3 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0]">
                <span><span class="text-[#e61919]">+</span> confirm password</span>
                <button type="button" class="text-[#e61919] hover:text-[#f4f4f0]" x-on:click="show = false">&times;</button>
            </div>
            <div class="p-5 sm:p-8">
                <p class="swiss-mono max-w-xl text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/70">
                    {{ __('For your security, please confirm your password to continue.') }}
                </p>
                <div class="mt-5" x-data="{}" x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
                    <input type="password" class="swiss-field block w-3/4"
                                placeholder="{{ __('Password') }}"
                                autocomplete="current-password"
                                x-ref="confirmable_password"
                                wire:model="confirmablePassword"
                                wire:keydown.enter="confirmPassword" />
                    @error('confirmable_password')
                        <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-[#0b0b0b] px-4 py-4 sm:px-8">
                <button type="button" class="swiss-btn" wire:click="stopConfirmingPassword" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="swiss-btn--primary" dusk="confirm-password-button" wire:click="confirmPassword" wire:loading.attr="disabled">
                    {{ __('Confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>

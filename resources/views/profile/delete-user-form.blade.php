<div>
    <p class="swiss-mono max-w-xl text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/65">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <div class="mt-6">
        <button type="button" class="swiss-btn--danger" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
            Terminate Account
        </button>
    </div>

    <!-- Delete User Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingUserDeletion').live }" x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false" x-show="show" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
        <div x-show="show" class="fixed inset-0" x-on:click="show = false">
            <div class="absolute inset-0 bg-[#0b0b0b]/80"></div>
        </div>
        <div x-show="show" class="relative mx-auto w-full max-w-2xl border-2 border-[#0b0b0b] bg-[#f4f4f0]">
            <div class="swiss-mono flex items-center justify-between gap-4 bg-[#0b0b0b] px-4 py-3 text-[10px] uppercase tracking-[0.25em] text-[#f4f4f0]">
                <span><span class="text-[#e61919]">+</span> confirm / terminate account</span>
                <button type="button" class="text-[#e61919] hover:text-[#f4f4f0]" x-on:click="show = false">&times;</button>
            </div>
            <div class="p-5 sm:p-8">
                <p class="swiss-mono max-w-xl text-[11px] uppercase leading-relaxed tracking-[0.12em] text-[#0b0b0b]/70">
                    {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
                <div class="mt-5" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" class="swiss-field block w-3/4"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="deleteUser" />
                    @error('password')
                        <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-[#0b0b0b] px-4 py-4 sm:px-8">
                <button type="button" class="swiss-btn" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="swiss-btn--danger"
                            wire:click="deleteUser"
                            wire:loading.attr="disabled">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

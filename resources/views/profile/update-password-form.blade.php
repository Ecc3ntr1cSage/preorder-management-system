<form wire:submit="updatePassword">
    <div class="grid gap-8 lg:grid-cols-2">
        <div>
            <label for="current_password" class="swiss-label"><span class="text-[#e61919]">+</span>02.1 current password</label>
            <input id="current_password" type="password" class="swiss-field" wire:model="state.current_password" autocomplete="current-password" />
            @error('current_password')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="swiss-label"><span class="text-[#e61919]">+</span>02.2 new password</label>
            <input id="password" type="password" class="swiss-field" wire:model="state.password" autocomplete="new-password" />
            @error('password')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="swiss-label"><span class="text-[#e61919]">+</span>02.3 confirm new password</label>
            <input id="password_confirmation" type="password" class="swiss-field" wire:model="state.password_confirmation" autocomplete="new-password" />
            @error('password_confirmation')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-8 flex items-center gap-4 border-t border-[#0b0b0b]/20 pt-5">
        <div x-data="{ shown: false, timeout: null }"
            x-init="@this.on('saved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
            x-show.transition.out.opacity.duration.1500ms="shown"
            x-transition:leave.opacity.duration.1500ms
            style="display: none;"
            class="swiss-mono text-[10px] uppercase tracking-[0.2em] text-[#e61919]">
            <samp>[ ok ] saved</samp>
        </div>

        <button type="submit" class="swiss-btn--primary ml-auto">Rotate Password &gt;&gt;&gt;</button>
    </div>
</form>

<form wire:submit="updateSocialLinks">
    <div class="grid gap-8 lg:grid-cols-2">
        <div>
            <label for="instagram" class="swiss-label"><span class="text-[#e61919]">+</span>03.1 instagram</label>
            <input id="instagram" type="text" class="swiss-field" wire:model="links.instagram" autocomplete="links.instagram" placeholder="handle" />
            @error('links.instagram')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tiktok" class="swiss-label"><span class="text-[#e61919]">+</span>03.2 tiktok</label>
            <input id="tiktok" type="text" class="swiss-field" wire:model="links.tiktok" autocomplete="links.tiktok" placeholder="handle" />
            @error('links.tiktok')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="facebook" class="swiss-label"><span class="text-[#e61919]">+</span>03.3 facebook</label>
            <input id="facebook" type="text" class="swiss-field" wire:model="links.facebook" autocomplete="links.facebook" placeholder="page" />
            @error('links.facebook')
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

        <button type="submit" class="swiss-btn--primary ml-auto" wire:loading.attr="disabled" wire:target="photo">
            <span wire:loading.remove wire:target="photo">{{ __('Commit Change') }} &gt;&gt;&gt;</span>
            <span wire:loading wire:target="photo">Transmitting &hellip;</span>
        </button>
    </div>
</form>

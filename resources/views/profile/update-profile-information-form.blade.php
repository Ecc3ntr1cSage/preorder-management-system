<form wire:submit="updateProfileInformation">
    <div class="grid gap-8 lg:grid-cols-2">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="lg:col-span-2">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <label for="photo" class="swiss-label"><span class="text-[#e61919]">+</span>photo / id card</label>

                <div class="flex flex-wrap items-end gap-4">
                    <div class="h-20 w-20 border-2 border-[#0b0b0b] bg-[#0b0b0b] p-1" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="h-full w-full object-cover">
                    </div>
                    <div class="h-20 w-20 border-2 border-[#e61919] p-1" x-show="photoPreview" style="display: none;">
                        <span class="block h-full w-full bg-cover bg-no-repeat bg-center"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>
                    <button type="button" class="swiss-btn" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select New Photo') }}
                    </button>
                    @if ($this->user->profile_photo_path)
                        <button type="button" class="swiss-btn--danger" wire:click="deleteProfilePhoto">
                            {{ __('Remove Photo') }}
                        </button>
                    @endif
                </div>
                @error('photo')
                    <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
                @enderror
            </div>
        @endif

        <div>
            <label for="name" class="swiss-label"><span class="text-[#e61919]">+</span>01.1 name</label>
            <input id="name" type="text" class="swiss-field" wire:model="state.name" required autocomplete="name" />
            @error('name')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="swiss-label"><span class="text-[#e61919]">+</span>01.2 email address</label>
            <input id="email" type="email" class="swiss-field" wire:model="state.email" required autocomplete="username" />
            @error('email')
                <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ err ] {{ $message }}</p>
            @enderror

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="swiss-mono mt-3 text-[10px] uppercase leading-relaxed tracking-[0.15em] text-[#0b0b0b]/60">
                    <span class="text-[#e61919]">[ ! ]</span> {{ __('Your email address is unverified.') }}
                    <button type="button" class="ml-1 underline decoration-[#e61919]/60 underline-offset-4 hover:text-[#e61919]"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>
                @if ($this->verificationLinkSent)
                    <p class="swiss-mono mt-2 text-[10px] uppercase tracking-[0.15em] text-[#e61919]">[ ok ] new verification link dispatched</p>
                @endif
            @endif
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

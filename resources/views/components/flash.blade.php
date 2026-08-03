<div
    x-cloak
    x-data="{
        shown: @js(session()->has('message')),
        message: @js(session('message', '')),
        kind: 'success',
        timeout: null,
        show(message, kind = 'success') {
            clearTimeout(this.timeout);
            this.message = message;
            this.kind = kind;
            this.shown = true;
            this.timeout = setTimeout(() => { this.shown = false }, 3600);
        }
    }"
    x-init="
        @this.on('success', ($event) => show($event.message, 'success'));
        @this.on('error', ($event) => show($event.message, 'error'));
        if (shown) timeout = setTimeout(() => { shown = false }, 3600);
    "
    x-show="shown"
    x-transition:enter="transition duration-700 ease-[cubic-bezier(.32,.72,0,1)]"
    x-transition:enter-start="translate-y-3 scale-[.97] opacity-0"
    x-transition:enter-end="translate-y-0 scale-100 opacity-100"
    x-transition:leave="transition duration-500 ease-[cubic-bezier(.32,.72,0,1)]"
    x-transition:leave-start="translate-y-0 scale-100 opacity-100"
    x-transition:leave-end="translate-y-2 scale-[.98] opacity-0"
    class="fixed right-4 top-5 z-50 w-[min(24rem,calc(100vw-2rem))] sm:right-6 sm:top-6"
    role="status"
    aria-live="polite"
>
    <div class="rounded-[1.5rem] bg-ink/5 p-1.5 shadow-[0_24px_70px_rgba(46,26,71,.18)] ring-1 ring-ink/10">
        <div class="flex items-start gap-4 rounded-[calc(1.5rem-0.375rem)] bg-[#171412] p-4 text-paper sm:p-5">
            <span
                class="flex size-9 shrink-0 items-center justify-center rounded-full"
                x-bind:class="kind === 'error' ? 'bg-accent/15 text-accent' : 'bg-moss/15 text-gold'"
            >
                <svg x-show="kind !== 'error'" viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="m6 12 4 4 8-8" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round" /><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.2" /></svg>
                <svg x-show="kind === 'error'" viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="M12 7v5m0 4h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /><path d="m10.3 3.8-7 12.1A2 2 0 0 0 5 18.9h14a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" /></svg>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em]" x-bind:class="kind === 'error' ? 'text-accent' : 'text-gold'" x-text="kind === 'error' ? 'Something needs attention' : 'Saved successfully'"></p>
                <p class="mt-1.5 text-sm leading-6 text-paper/75" x-text="message"></p>
            </div>
            <button type="button" x-on:click="shown = false" class="flex size-7 shrink-0 items-center justify-center rounded-full bg-paper/10 text-paper/55 transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:bg-paper/20 hover:text-paper" aria-label="Dismiss message"><svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" /></svg></button>
        </div>
    </div>
</div>

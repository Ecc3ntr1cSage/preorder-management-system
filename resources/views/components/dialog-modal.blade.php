@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="rounded-[calc(2rem-0.375rem)] bg-paper">
        <div class="flex items-start justify-between gap-6 px-6 py-6 sm:px-8">
            <div class="text-[10px] font-semibold uppercase tracking-[0.22em] text-moss">
            {{ $title }}
            </div>
            <button type="button" class="flex size-8 shrink-0 items-center justify-center rounded-full bg-ink/5 text-ink/55 transition-all duration-700 ease-[cubic-bezier(.32,.72,0,1)] hover:-translate-y-0.5 hover:bg-accent hover:text-white" x-on:click="show = false" aria-label="Close dialog"><svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" /></svg></button>
        </div>
        <div class="px-6 pb-8 text-sm text-ink/70 sm:px-8">
            {{ $content }}
        </div>
    </div>
</x-modal>

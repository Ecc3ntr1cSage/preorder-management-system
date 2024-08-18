@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4 bg-gray-700 rounded-md">
        <div class="text-lg font-semibold text-center text-indigo-400 lg:text-2xl">
            {{ $title }}
            <button type="button" class="float-right px-2 transition rounded-md hover:bg-gray-500" x-on:click="show = false">&times</button>
        </div>
        <div class="mt-4 text-sm text-gray-200">
            {{ $content }}
        </div>
    </div>
</x-modal>

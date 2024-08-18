@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Overview') }}
</x-slot>
<div class="grid grid-cols-2">
    <x-admin-panel>
        <x-slot name="title">
            {{ __('All Users') }}
        </x-slot>
        <div class="flex items-center gap-2">
            <select wire:model.live="perPage"
                class="text-xs text-gray-200 transition border-none rounded-md bg-neutral-800 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="">Max</option>
            </select>
            <x-input class="w-48 text-xs text-gray-200" placeholder="Search username or title"
                wire:model.live.debounce.500ms="search" type="text" />
            <button class="ml-2 text-xs text-gray-300 underline transition hover:text-orange-400"
                wire:click="resetFilter">Reset</button>
        </div>
        <x-admin-table>
            <thead class="text-xs font-medium text-left text-indigo-400 uppercase bg-neutral-800">
                <tr>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider ">
                        Email
                    </th>
                    <th scope="col" class="flex items-center px-3 py-4 tracking-wider">
                        Date Joined
                    </th>
                </tr>
            </thead>
            <tbody class="bg-neutral-800">
                @foreach ($users as $user)
                    <tr wire:key="{{ $user->id }}" wire:loading.class="opacity-50"
                        class="transition bg-black/20 hover:bg-neutral-800 hover:text-indigo-400">
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $user->name }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            @if ($user->email_verified_at)
                                <p class="text-green-400">{{ $user->email }}</p>
                            @else
                                <p class="text-rose-400">{{ $user->email }}</p>
                            @endif
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $carbon::parse($user->created_at)->setTimeZone('Asia/Manila')->format('d F Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin-table>
        {{ $users->links() }}
    </x-admin-panel>
</div>

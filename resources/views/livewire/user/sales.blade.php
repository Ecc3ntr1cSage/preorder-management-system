@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Sales Order') }}
</x-slot>
<x-dashboard-panel>
    <div class="min-h-screen mx-auto">
        <div class="flex items-center gap-2">
            <x-dropdown align="left">
                <x-slot name="trigger">
                    <button
                        class="px-2 py-1.5 my-2 text-indigo-400 bg-gray-800 rounded-md hover:bg-gray-900 focus:border-violet-500 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <ul class="m-1 text-xs">
                        <p class="px-4 py-2 text-xs text-gray-400">Filter by Campaign</p>
                        @foreach ($campaigns as $campaign)
                            <button type="button" class="w-full px-4 py-2 text-left rounded-md hover:bg-indigo-500/80"
                                wire:click="sortOrder({{ $campaign->id }})">
                                {{ $campaign->title }}
                            </button>
                        @endforeach
                    </ul>
                </x-slot>
            </x-dropdown>
            <select wire:model.live="perPage"
                class="text-xs text-gray-200 transition bg-gray-800 border-none rounded-md focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="">Max</option>
            </select>
            <x-input class="w-48 text-xs" placeholder="Search email or name" wire:model.lazy="search" type="text" />
            <x-dropdown align="left">
                <x-slot name="trigger">
                    <button
                        class="px-2 py-1.5 my-2 text-indigo-400 bg-gray-800 rounded-md hover:bg-gray-900 focus:border-violet-500 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <ul class="m-1 text-xs">
                        <p class="px-4 py-2 text-xs text-gray-400">Export to Excel</p>
                        @foreach ($campaigns as $campaign)
                            <button type="button" class="w-full px-4 py-2 text-left rounded-md hover:bg-indigo-500/80"
                                wire:click="export({{ $campaign->id }})">
                                {{ $campaign->title }}
                            </button>
                        @endforeach
                    </ul>
                </x-slot>
            </x-dropdown>
            <button class="ml-2 text-xs text-gray-600 underline transition hover:text-indigo-700"
                wire:click="clearFilter">Clear Filter</button>
        </div>
        <!-- Component Start -->
        <div class="mb-2 overflow-hidden overflow-x-scroll rounded-lg">
            <table class="w-full text-xs text-gray-300 rounded-lg table-auto">
                <thead class="text-xs font-medium text-indigo-400 uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-3 py-4 tracking-wider text-left">
                            Email
                        </th>
                        <th scope="col" class="flex items-center px-3 py-4 tracking-wider text-left ">
                            Name
                            <span>
                                @if ($this->column == 'name' && $this->direction == 'desc')
                                    <svg wire:click="sort_direction('name','asc')" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                                    </svg>
                                @elseif($this->column == 'name' && $this->direction == 'asc')
                                    <svg wire:click="sort_direction('amount','desc')" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                                    </svg>
                                @else
                                    <svg wire:click="sort_direction('name','desc')" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                @endif
                            </span>
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider text-left">
                            Phone
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider text-left">
                            Qty
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider text-left">
                            Variations
                        </th>
                        <th scope="col" class="flex items-center px-3 py-4 tracking-wider text-left ">
                            Amount
                            <span>
                                @if ($this->column == 'amount' && $this->direction == 'desc')
                                    <svg wire:click="sort_direction('amount','asc')" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                                    </svg>
                                @elseif($this->column == 'amount' && $this->direction == 'asc')
                                    <svg wire:click="sort_direction('amount','desc')"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                                    </svg>
                                @else
                                    <svg wire:click="sort_direction('amount','desc')"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                @endif
                            </span>
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider text-left">
                            Address
                        </th>
                        <th scope="col" class="flex items-center px-3 py-4 tracking-wider text-left ">
                            Paid At
                            <span>
                                @if ($this->column == 'paid_at' && $this->direction == 'desc')
                                    <svg wire:click="sort_direction('paid_at','asc')"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                                    </svg>
                                @elseif($this->column == 'paid_at' && $this->direction == 'asc')
                                    <svg wire:click="sort_direction('paid_at','desc')"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                                    </svg>
                                @else
                                    <svg wire:click="sort_direction('paid_at','desc')"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-4 cursor-pointer hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                @endif
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800">
                    @foreach ($orders as $order)
                        <tr wire:key="{{ $order->id }}" wire:loading.class="opacity-50"
                            class="transition bg-black/20 hover:bg-gray-800 hover:text-indigo-400">
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $order->email }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $order->name }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $order->phone }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $order->quantity }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $order->variations }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <p class="px-2 py-1 text-gray-800 bg-green-400 rounded-full w-fit">
                                    {{ number_format(($order->amount - $order->fee) / 100, 2) }}</p>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <p>{{ $order->address }}</p>
                                <p>{{ $order->postcode }}, {{ $order->state }}</p>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $carbon::parse($order->paid_at)->format('j/n/Y g:iA') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
</x-dashboard-panel>

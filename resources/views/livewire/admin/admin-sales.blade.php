@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Sales') }}
</x-slot>
<x-admin-panel>
    <x-slot name="title">
        {{ __('Sales Order') }}
    </x-slot>
    <div class="flex items-center gap-2">
        <select wire:model.live="perPage"
            class="text-xs text-gray-200 transition border-none rounded-md bg-neutral-800 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="">Max</option>
        </select>
        <x-input class="w-48 text-xs text-gray-200" placeholder="Search title or email"
            wire:model.live.debounce.500ms="search" type="text" />
        <button class="ml-2 text-xs text-gray-300 underline transition hover:text-orange-400"
            wire:click="resetFilter">Reset</button>
    </div>
    <x-admin-table>
        <thead class="text-xs font-medium text-left text-indigo-400 uppercase bg-neutral-800">
            <tr>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Title
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Email
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Name
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Phone
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Qty
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Variations
                </th>
                <th scope="col" class="flex items-center px-3 py-4 tracking-wider">
                    Amount
                    <span>
                        @if ($this->column == 'amount' && $this->direction == 'desc')
                            <svg wire:click="sort('amount','asc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                            </svg>
                        @elseif($this->column == 'amount' && $this->direction == 'asc')
                            <svg wire:click="sort('amount','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                            </svg>
                        @else
                            <svg wire:click="sort('amount','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                            </svg>
                        @endif
                    </span>
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Address
                </th>
                <th scope="col" class="flex items-center px-3 py-4 tracking-wider">
                    Paid At
                    <span>
                        @if ($this->column == 'paid_at' && $this->direction == 'desc')
                            <svg wire:click="sort('paid_at','asc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                            </svg>
                        @elseif($this->column == 'paid_at' && $this->direction == 'asc')
                            <svg wire:click="sort('paid_at','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                            </svg>
                        @else
                            <svg wire:click="sort('paid_at','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                            </svg>
                        @endif
                    </span>
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Link
                </th>
            </tr>
        </thead>
        <tbody class="text-left bg-neutral-800">
            @foreach ($orders as $order)
                <tr wire:key="{{ $order->id }}" wire:loading.class="opacity-50"
                    class="transition bg-black/20 hover:bg-neutral-800 hover:text-indigo-400">
                    <td class="px-3 py-4 capitalize whitespace-nowrap">
                        {{ $order->campaign->title }}
                    </td>
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
                    <td class="flex items-center gap-1 px-3 py-4 whitespace-nowrap">
                        <p class="px-2 py-1 text-gray-800 bg-green-400 rounded-full w-fit">
                            {{ number_format($order->amount / 100, 2) }}
                            ({{ number_format($order->shipping / 100, 2) }})
                        </p>
                        <p class="px-2 py-1 text-gray-800 bg-purple-400 rounded-full w-fit">
                            {{ number_format($order->fee / 100, 2) }}
                        </p>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->postcode }}, {{ $order->state }}</p>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        {{ $carbon::parse($order->paid_at)->format('j/n/y g:iA') }}
                    </td>
                    <td class="flex items-center gap-1 px-3 py-4 whitespace-nowrap">
                        <a href="{{ route('invoice', $order->billplz_id) }}" wire:navigate
                            class="p-1 transition rounded-md bg-sky-500 hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-admin-table>
    {{ $orders->links() }}
</x-admin-panel>

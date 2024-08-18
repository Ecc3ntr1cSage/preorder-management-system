@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Campaigns') }}
</x-slot>
<x-admin-panel>
    <x-slot name="title">
        {{ __('All Campaigns') }}
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
                    User
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider ">
                    Title
                </th>
                <th scope="col" class="flex items-center px-3 py-4 tracking-wider">
                    Price
                    <span>
                        @if ($this->column == 'price' && $this->direction == 'desc')
                            <svg wire:click="sort('price','asc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                            </svg>
                        @elseif($this->column == 'price' && $this->direction == 'asc')
                            <svg wire:click="sort('price','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                            </svg>
                        @else
                            <svg wire:click="sort('price','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                            </svg>
                        @endif
                    </span>
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Start Date
                </th>
                <th scope="col" class="flex items-center px-3 py-4 tracking-wider">
                    End Date
                    <span>
                        @if ($this->column == 'end_date' && $this->direction == 'desc')
                            <svg wire:click="sort('end_date','asc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                            </svg>
                        @elseif($this->column == 'end_date' && $this->direction == 'asc')
                            <svg wire:click="sort('end_date','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                            </svg>
                        @else
                            <svg wire:click="sort('end_date','desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-4 cursor-pointer hover:text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                            </svg>
                        @endif
                    </span>
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider ">
                    Slug
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider">
                    Variations
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider ">
                    Shipping
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider ">
                    Status
                </th>
                <th scope="col" class="px-3 py-4 tracking-wider ">
                    More
                </th>
            </tr>
        </thead>
        <tbody class="bg-neutral-800">
            @foreach ($campaigns as $campaign)
                <tr wire:key="{{ $campaign->id }}" wire:loading.class="opacity-50"
                    class="transition bg-black/20 hover:bg-neutral-800 hover:text-indigo-400">
                    <td class="px-3 py-4 whitespace-nowrap">
                        {{ $campaign->user->name }} 
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        <p class="capitalize">{{ $campaign->title }} ({{ $campaign->visitors->count() }})</p>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        {{ $campaign->currency }} {{ number_format($campaign->price / 100, 2) }}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        {{ $carbon::parse($campaign->start_date)->format('d-m-y') }}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        {{ $carbon::parse($campaign->end_date)->format('d-m-y') }}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        {{ $campaign->slug }}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        @foreach (json_decode($campaign->variations, true) as $variation)
                            <p>{{ $variation['name'] }}: {{ $variation['values'] }}</p>
                        @endforeach
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        @if (!empty(array_filter(json_decode($campaign->shipping, true))))
                            @foreach (json_decode($campaign->shipping, true) as $shipping)
                                ({{ $shipping }})
                            @endforeach
                        @else
                            <p>Free shipping</p>
                        @endif
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        @if ($campaign->status == 1)
                            <p class="px-2 py-1 text-center text-gray-800 bg-green-400 rounded-full">Active</p>
                        @else
                            <p class="px-2 py-1 text-gray-800 bg-orange-400 rounded-full">Ended</p>
                        @endif
                    </td>
                    <td class="flex items-center gap-1 px-3 py-4 whitespace-nowrap">
                        <a href="{{ route('campaign.show', $campaign->slug) }}" wire:navigate
                            class="p-1 transition rounded-md bg-sky-500 hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.campaign.info', $campaign->slug) }}" wire:navigate
                            class="p-1 transition bg-purple-500 rounded-md hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-admin-table>
    {{ $campaigns->links() }}
</x-admin-panel>

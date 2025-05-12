@inject('carbon', 'Carbon\Carbon')
<section class="min-h-screen p-6 mx-auto max-w-7xl">
    <div class="mb-2 overflow-hidden overflow-x-scroll rounded-lg">
        <table class="w-full text-xs text-gray-300 rounded-lg table-auto">
            <thead class="text-xs font-medium text-indigo-400 uppercase bg-gray-800">
                <tr>
                    <th scope="col" class="px-3 py-2 tracking-wider text-left">
                        Product Title
                    </th>
                    <th scope="col" class="px-3 py-2 tracking-wider text-left">
                        Amount
                    </th>
                    <th scope="col" class="px-3 py-2 tracking-wider text-left">
                        Quantity
                    </th>
                    <th scope="col" class="px-3 py-2 tracking-wider text-left">
                        Paid At
                    </th>
                    <th scope="col" class="px-3 py-2 tracking-wider text-left">
                        Address
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-gray-800">
                @foreach ($orders as $order)
                    <tr wire:key="{{ $order->id }}" wire:loading.class="opacity-50"
                        class="transition bg-black/20 hover:bg-gray-800 hover:text-indigo-400 group">
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{ $order->campaign->title }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            RM {{ number_format($order->amount / 100, 2) }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{ $order->quantity }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{ $carbon::parse($order->paid_at)->format('j/n/Y g:iA') }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            <p>{{ $order->address }}</p>
                            <p>{{ $order->postcode }}, {{ $order->state }}</p>
                        </td>
                        <td>
                            <a href="{{ route('customer.invoice', $order) }}" wire:navigate
                                class="transition-opacity duration-200 opacity-0 cursor-pointer group-hover:opacity-100 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d=" M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5
                                7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5
                                2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0
                                1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {{ $orders->links() }} --}}
    </div>
</section>

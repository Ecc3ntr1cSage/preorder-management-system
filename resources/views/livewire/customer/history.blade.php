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
                    <th>Details</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800">
                @foreach ($orders as $order)
                    <tr wire:key="{{ $order->id }}" wire:loading.class="opacity-50"
                        class="transition bg-black/20 hover:bg-gray-800 hover:text-indigo-400">
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
                        <td class="text-center">
                            <a href="{{ route('customer.invoice', $order) }}" wire:navigate
                                class="inline-flex justify-center p-1 rounded-md w-fit hover:bg-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
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

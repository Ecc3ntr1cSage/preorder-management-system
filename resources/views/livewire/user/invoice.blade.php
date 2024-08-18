@inject('carbon', 'Carbon\Carbon')
<div class="max-w-2xl min-h-screen p-1 mx-auto lg:p-6">
    <div class="flex flex-col p-4 bg-gray-800 rounded-lg shadow-md sm:p-10">
        <div class="flex justify-between">
            <div>
                <img src="{{ asset('asset/xito.png') }}" alt="" class="w-10 h-10" />
                <h1 class="mt-2 text-lg font-semibold text-blue-600 md:text-xl dark:text-white">HopeXito</h1>
            </div>
            <div class="text-end">
                <h2 class="text-2xl font-semibold md:text-3xl dark:text-gray-200">Invoice #
                </h2>
                <span class="block mt-1 text-gray-400">{{ $order->billplz_id }}</span>
            </div>
        </div>
        <div class="grid gap-3 mt-8 sm:grid-cols-2">
            <div>
                <h3 class="text-lg font-semibold text-gray-200">Bill to:</h3>
                <h3 class="text-lg font-semibold text-gray-200">{{ $order->name }}</h3>
                <address class="mt-2 not-italic text-gray-400">
                    {{ $order->address }}<br>
                    {{ $order->postcode }}<br>
                    <span class="capitalize">{{ $order->state }}</span>
                </address>
            </div>
            <div class="space-y-2 sm:text-end">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-2">
                    <dl class="grid sm:grid-cols-5 gap-x-3">
                        <dt class="col-span-3 font-semibold text-gray-200">Paid at:
                        </dt>
                        <dd class="col-span-2 text-gray-400">
                            {{ $carbon::parse($order->paid_at)->format('n/j/Y g:iA') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <div class="p-4 space-y-4 border border-gray-200 rounded-lg dark:border-gray-700">
                <div class="hidden sm:grid sm:grid-cols-8">
                    <div class="text-xs font-medium text-gray-400 uppercase sm:col-span-4">Item</div>
                    <div class="text-xs font-medium text-gray-400 uppercase text-start sm:col-span-1">Qty</div>
                    <div class="text-xs font-medium text-gray-400 uppercase text-start sm:col-span-2">Variations</div>
                    <div class="text-xs font-medium text-gray-400 uppercase text-end">Amount</div>
                </div>
                <div class="hidden border-b border-gray-200 sm:block dark:border-gray-700"></div>
                <div class="grid items-center grid-cols-3 gap-2 text-xs sm:grid-cols-8">
                    <div class="col-span-full sm:col-span-4">
                        <h5 class="font-medium text-gray-400 uppercase sm:hidden">Item</h5>
                        <p class="text-sm font-medium text-gray-200 capitalize">{{ $order->campaign->title }}</p>
                    </div>
                    <div class="sm:col-span-1">
                        <h5 class="font-medium text-gray-400 uppercase sm:hidden">Qty</h5>
                        <p class="text-gray-200">{{ $order->quantity }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <h5 class="font-medium text-gray-400 uppercase sm:hidden">Variations</h5>
                        <p class="text-gray-200 uppercase">{{ $order->variations }}</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-400 uppercase sm:hidden">Amount</h5>
                        <p class="text-gray-200 sm:text-end">
                            RM{{ number_format((($order->amount + $order->discount - $order->shipping) / $order->quantity) / 100, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex mt-8 sm:justify-end">
            <div class="w-full max-w-2xl space-y-2 sm:text-end">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-2">
                    <dl class="grid sm:grid-cols-5 gap-x-3">
                        <dt class="col-span-3 font-semibold text-gray-200">Subtotal:</dt>
                        <dd class="col-span-2 text-gray-200">
                            RM{{ number_format(($order->amount + $order->discount - $order->shipping) / 100, 2) }}</dd>
                    </dl>
                    <dl class="grid sm:grid-cols-5 gap-x-3">
                        <dt class="col-span-3 font-semibold text-gray-200">Shipping</dt>
                        @if ($order->shipping == 0)
                            <dd class="col-span-2 text-gray-200">Free shipping</dd>
                        @else
                            <dd class="col-span-2 text-gray-200">RM{{ number_format($order->shipping / 100, 2) }}</dd>
                        @endif
                    </dl>
                    @if ($order->discount != 0)
                    <dl class="grid sm:grid-cols-5 gap-x-3">
                        <dt class="col-span-3 font-semibold text-gray-200">Discount</dt>
                        <dd class="col-span-2 text-gray-200">
                            - RM{{ number_format($order->discount / 100, 2) }}</dd>
                    </dl>
                    @endif
                    <dl class="grid sm:grid-cols-5 gap-x-3">
                        <dt class="col-span-3 font-semibold text-indigo-400">Amount paid:
                        </dt>
                        <dd class="col-span-2 text-indigo-400">RM{{ number_format($order->amount / 100, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div
        class="flex flex-col p-4 mt-1 space-y-5 transition bg-gray-800 rounded-lg shadow-md sm:px-10 hover:shadow-indigo-500/30">
        <p class="font-semibold text-gray-200">Order Progress</p>
        <div class="relative">
            <p class="absolute w-full h-2 rounded-full bg-neutral-800"></p>
            @if ($order->status == 1)
                <p class="absolute w-[10%] h-2 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 rounded-full">
                </p>
            @elseif($order->status == 2)
                <p class="absolute h-2 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 rounded-full w-[40%]">
                </p>
            @elseif($order->status == 3)
                <p class="absolute w-[70%] h-2 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 rounded-full">
                </p>
            @elseif($order->status == 4)
                <p class="absolute w-full h-2 rounded-full bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500">
                </p>
            @endif
        </div>
        <div class="flex justify-between px-2 text-xs text-gray-200">
            <p>Preorder</p>
            <p>Campaign Ended</p>
            <p>Shipped</p>
            <p>Delivered</p>
        </div>
    </div>
    <x-flash />
</div>

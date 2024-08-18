<x-slot name="header">
    {{ __('Analytics') }}
</x-slot>
<div class="grid grid-cols-2">
    <x-admin-panel>
        <x-slot name="title">
            {{ __('Sales') }}
        </x-slot>
        <div class="grid grid-cols-5 p-2 text-center divide-x">
            <div>
                <p class="text-lg text-lime-400">{{ number_format($orders->sum('amount') / 100, 2) }}</p>
                <p class="text-xs text-gray-300">Sales</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($orders->sum('fee') / 100, 2) }}</p>
                <p class="text-xs text-gray-300">Service Charges</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($orders->sum('shipping') / 100, 2) }}</p>
                <p class="text-xs text-gray-300">Shipping</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ $orders->sum('quantity') }}</p>
                <p class="text-xs text-gray-300">Quantity</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($orders->sum('discount') / 100, 2) }}</p>
                <p class="text-xs text-gray-300">Discount</p>
            </div>
        </div>
    </x-admin-panel>
    <x-admin-panel>
        <x-slot name="title">
            {{ __('Wallets') }}
        </x-slot>
        <div class="grid grid-cols-4 p-2 text-center divide-x">
            <div>
                <p class="text-lg text-lime-400">{{ number_format($wallets->sum('earning') / 100, 2) }}</p>
                <p class="text-xs text-gray-300">Earnings</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($wallets->sum('balance') / 100, 2) }}</p>
                <p class="text-xs text-gray-300">Balance</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($transactions->sum('withdrawn_amount') / 100, 2) }}
                </p>
                <p class="text-xs text-gray-300">Withdrawal</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ $transactions->count() }}</p>
                <p class="text-xs text-gray-300">Transactions</p>
            </div>
        </div>
    </x-admin-panel>
    <x-admin-panel>
        <x-slot name="title">
            {{ __('Campaigns') }}
        </x-slot>
        <div class="grid grid-cols-4 p-2 text-center divide-x">
            <div>
                <p class="text-lg text-lime-400">{{ $campaigns->where('status', 1)->count() }}</p>
                <p class="text-xs text-gray-300">Active</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ $campaigns->where('status', 2)->count() }}</p>
                <p class="text-xs text-gray-300">Ended</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($averageCampaignDuration, 0) }} <span class="text-sm">Days</span>
                </p>
                <p class="text-xs text-gray-300">Average Duration</p>
            </div>
            <div>
                <p class="text-lg text-lime-400">{{ number_format($averagePrice/100, 2) }}</p>
                <p class="text-xs text-gray-300">Average Price</p>
            </div>
        </div>
    </x-admin-panel>
</div>

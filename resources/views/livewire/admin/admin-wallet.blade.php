@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Wallets') }}
</x-slot>
<div>
    <x-admin-panel>
        <x-slot name="title">
            {{ __('Withdrawal Request') }}
        </x-slot>
        <x-admin-table>
            <thead class="text-xs font-medium text-center text-indigo-400 uppercase bg-neutral-800">
                <tr>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Account Name
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Bank Name
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Accout Number
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Balance
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Amount
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Updated Balance
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-3 py-4 tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-neutral-800">
                @foreach ($wallets->where('status', '=', 2) as $wallet)
                    <tr wire:key="{{ $wallet->id }}"
                        class="text-center transition bg-black/20 hover:bg-neutral-800 hover:text-indigo-400">
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $wallet->user->name }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $wallet->bank_holder_name }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $wallet->bank_name }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $wallet->bank_account_number }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ number_format($wallet->withdrawal->current_balance / 100, 2) }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ number_format($wallet->withdrawal->withdrawn_amount / 100, 2) }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ number_format($wallet->withdrawal->final_balance / 100, 2) }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            {{ $carbon::parse($wallet->withdrawal->created_at)->setTimeZone('Asia/Manila')->format('d-m-y, g:i A') }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <button type="button" class="p-1 rounded-md hover:bg-black/20"
                                wire:click="$toggle('approveModal')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </button>
                            <x-confirmation-modal wire:model="approveModal" maxWidth="md">
                                <x-slot name="title">
                                    Approval Confirmation
                                </x-slot>
                                <x-slot name="content">
                                    Approve withdrawal request, this action is irreversible.
                                </x-slot>
                                <x-slot name="footer">
                                    <x-secondary-button wire:click="$toggle('approveModal')"
                                        wire:loading.attr="disabled">
                                        Nevermind
                                    </x-secondary-button>
                                    <x-button-custom type="button" class="ml-2"
                                        wire:click="approveWithdraw({{ $wallet->id }})" wire:loading.attr="disabled">
                                        Approve
                                    </x-button-custom>
                                </x-slot>
                            </x-confirmation-modal>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin-table>
    </x-admin-panel>
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <x-admin-panel>
            <x-slot name="title">
                {{ __('Wallet List') }}
            </x-slot>
            <div class="flex items-center gap-2">
                <select wire:model.live="walletPerPage"
                    class="text-xs text-gray-200 transition border-none rounded-md bg-neutral-800 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="">Max</option>
                </select>
                <x-input class="w-48 text-xs text-gray-200" placeholder="Search username"
                    wire:model.live.debounce.500ms="searchWallet" type="text" />
                <button class="ml-2 text-xs text-gray-300 underline transition hover:text-orange-400"
                    wire:click="resetWallet">Reset</button>
            </div>
            <x-admin-table>
                <thead class="text-xs font-medium text-center text-indigo-400 uppercase bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Account Name
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Bank Details
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Earning
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Balance
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-neutral-800">
                    @foreach ($wallets as $wallet)
                        <tr wire:key="{{ $wallet->id }}" wire:loading.class="opacity-50"
                            class="text-center transition bg-black/20 hover:bg-neutral-800 hover:text-indigo-400">
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $wallet->user->name }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $wallet->bank_holder_name }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <p>{{ $wallet->bank_name }}</p>
                                <p>{{ $wallet->bank_account_number }}</p>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ number_format($wallet->earning / 100, 2) }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ number_format($wallet->balance / 100, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin-table>
        </x-admin-panel>
        <x-admin-panel>
            <x-slot name="title">
                {{ __('Recent Transactions') }}
            </x-slot>
            <div class="flex items-center gap-2">
                <select wire:model.live="transactionPerPage"
                    class="text-xs text-gray-200 transition border-none rounded-md bg-neutral-800 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="">Max</option>
                </select>
                <x-input class="w-48 text-xs text-gray-200" placeholder="Search username"
                    wire:model.live.debounce.500ms="searchTransaction" type="text" />
                <button class="ml-2 text-xs text-gray-300 underline transition hover:text-orange-400"
                    wire:click="resetTransaction">Reset</button>
            </div>
            <x-admin-table>
                <thead class="text-xs font-medium text-indigo-400 uppercase bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Balance
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider ">
                            Amount
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Updated Balance
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-3 py-4 tracking-wider">
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center bg-neutral-800">
                    @foreach ($transactions as $transaction)
                        <tr wire:key="{{ $transaction->id }}" wire:loading.class="opacity-50"
                            class="transition bg-black/20 hover:bg-neutral-800 hover:text-indigo-400">
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $transaction->wallet->user->name }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ number_format($transaction->current_balance / 100, 2) }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @if ($transaction->withdrawn_amount == 0)
                                    <p class="px-2 py-0.5 text-gray-800 bg-green-400 rounded-full">+
                                        {{ number_format($transaction->credited_amount / 100, 2) }}</p>
                                @else
                                    <p class="px-2 py-0.5 text-gray-800 bg-red-400 rounded-full">-
                                        {{ number_format($transaction->withdrawn_amount / 100, 2) }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ number_format($transaction->final_balance / 100, 2) }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @if ($transaction->status == 1)
                                    <p class="px-2 py-0.5 text-gray-800 rounded-full bg-green-400 ">Income</p>
                                @elseif($transaction->status == 2)
                                    <p class="px-2 py-0.5 text-gray-800 rounded-full bg-emerald-400 ">Approved
                                    </p>
                                @else
                                    <p class="px-2 py-0.5 text-gray-800 rounded-full bg-red-400 ">Pending</p>
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                {{ $carbon::parse($transaction->created_at)->setTimeZone('Asia/Manila')->format('d F y, g:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin-table>
            {{ $transactions->links() }}
        </x-admin-panel>
    </div>
    <x-flash />
</div>

@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Payouts') }}
</x-slot>
<section class="grid grid-cols-1 gap-2 lg:grid-cols-2 lg:gap-0">
    <x-dashboard-panel>
        <div
            class="p-1 rounded-lg shadow-md shadow-gray-700/70 bg-gradient-to-tr from-sky-500 via-indigo-500 to-purple-500">
            <div class="grid grid-cols-3 p-4 text-center text-gray-200 bg-gray-800 divide-x rounded-md">
                <div>
                    <p class="text-lg tracking-wide">RM {{ number_format($wallet->earning / 100, 2) }}</p>
                    <p class="text-sm text-gray-400">Total Earnings</p>
                </div>
                <div>
                    <p class="text-lg tracking-wide">RM {{ number_format($wallet->balance / 100, 2) }}</p>
                    <p class="text-sm text-gray-400">Available Earnings</p>
                </div>
                <div>
                    <p class="text-lg tracking-wide">RM {{ number_format(($wallet->earning - $wallet->balance) / 100, 2) }}</p>
                    <p class="text-sm text-gray-400">Total Withdrawal</p>
                </div>
            </div>
        </div>
        <x-button type="button" class="w-full mx-auto mt-4 lg:w-96" target="validateWithdrawModal"
            wire:click="validateWithdrawModal">Withdraw Earnings</x-button>
        <hr class="my-3 border border-indigo-500/70" />
        <form wire:submit.prevent="updateBankDetails" class="p-6 m-1 mt-6 text-gray-200 bg-gray-800 rounded-lg">
            @csrf
            <x-label value="{{ __('Account Holder Name') }}" />
            <x-input type="text" wire:model="name" class="w-full" />
            <x-input-error for="name" />
            <x-label value="{{ __('Bank Name') }}" class="" />
            <x-input type="text" wire:model="bankName" class="w-full" />
            <x-input-error for="bankName" />
            <x-label value="{{ __('Account Number') }}" class="" />
            <x-input type="text" wire:model="accountNumber" class="w-full" />
            <x-input-error for="accountNumber" />
            <x-button-custom type="submit" class="mx-auto mt-6">Save</x-button-custom>
        </form>
    </x-dashboard-panel>
    <x-dashboard-panel>
        <div class="min-h-screen mx-auto">
            <p class="text-lg font-bold tracking-wider text-gray-700">Past Transactions</p>
            <!-- Component Start -->
            <div class="my-2 overflow-hidden overflow-x-scroll rounded-lg">
                <table class="w-full text-xs text-center text-gray-300 rounded-lg table-auto">
                    <thead class="text-xs font-medium text-indigo-400 uppercase bg-gray-800">
                        <tr>
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
                    <tbody class="text-center bg-gray-800">
                        @foreach ($wallet->transactions()->orderByDesc('created_at')->get() as $transaction)
                            <tr wire:key="{{ $transaction->id }}"
                                class="transition bg-black/20 hover:bg-gray-800 hover:text-indigo-400">
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
                                        <p class="px-2 py-0.5 text-gray-800 rounded-full bg-emerald-400 ">Approved</p>
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
                </table>
            </div>
        </div>
    </x-dashboard-panel>
    <x-dialog-modal wire:model="withdrawModal" maxWidth="md">
        <x-slot name="title">
            Withdraw Your Earnings
        </x-slot>
        <x-slot name="content">
            <div class="space-y-2">
                <x-label value="{{ __('Account Holder Name') }}" />
                <p class="px-2 py-1 bg-gray-600 rounded-md w-fit">{{ $wallet->bank_holder_name }}</p>
                <x-label value="{{ __('Bank Name') }}" />
                <p class="px-2 py-1 bg-gray-600 rounded-md w-fit">{{ $wallet->bank_name }}</p>
                <x-label value="{{ __('Account Number') }}" />
                <p class="px-2 py-1 bg-gray-600 rounded-md w-fit">{{ $wallet->bank_account_number }}</p>
                <x-label value="{{ __('Withdrawal Amount') }}" />
                <form wire:submit.prevent="withdrawEarning">
                    @csrf
                    <x-input type="text" wire:model="withdrawAmount" placeholder="Eg. 2000" class="w-full" />
                    <x-input-error for="withdrawAmount" />
                    <x-button type="submit" target="withdrawEarning" class="w-full mt-3">Withdraw Now</x-button>
                </form>
                <hr class="border border-indigo-500/70" />
                <p class="font-bold">Disclaimer:</p>
                <ul class="ml-4 list-disc">
                    <li>Please ensure that all information provided are accurate.</li>
                    <li>Earnings withdrawal request will be processed according to the information provided here.</li>
                    <li>Once the withdrawal amount has been transferred, the action will not be reversible.</li>
                </ul>
            </div>
        </x-slot>
    </x-dialog-modal>
    <x-flash />
</section>

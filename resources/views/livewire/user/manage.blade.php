@inject('carbon', 'Carbon\Carbon')
<x-slot name="header">
    {{ __('Campaigns') }}
</x-slot>
<x-dashboard-panel>
    <p class="mb-4 text-xl leading-tight tracking-wider">Ongoing</p>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 md:grid-cols-2">
        @foreach ($campaigns as $campaign)
            @php
                $endDate = $carbon::parse($campaign->end_date);
                $today = $carbon::today();
                $daysLeft = $endDate->diffInDays($today);
            @endphp
            <a href="{{ route('campaign.info', $campaign->slug) }}" wire:navigate class="relative block bg-black group">
                <img alt="Campaign" src="{{ asset('storage/campaign/' . $campaign->images->first()->image) }}"
                    class="absolute inset-0 object-cover w-full h-full transition-opacity opacity-75 group-hover:opacity-50" />
                <p
                    class="absolute top-0 right-0 px-2 py-1 text-xs tracking-wide uppercase rounded-bl-lg text-rose-400 bg-black/70">
                    {{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }} left</p>
                <div class="relative p-4 sm:p-6 lg:p-8">
                    <p
                        class="px-2 py-1 text-sm font-medium tracking-wider text-indigo-400 uppercase rounded-md bg-black/70 w-fit">
                        {{ $carbon::parse($campaign->start_date)->format('d F Y') }} -
                        {{ $carbon::parse($campaign->end_date)->format('d F Y') }}
                    </p>
                    <p class="text-xl font-bold text-white capitalize sm:text-2xl">{{ $campaign->title }}</p>
                    <div class="mt-32 sm:mt-48 lg:mt-64">
                        <div
                            class="transition-all transform translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
                            <p class="text-sm text-white">
                                {{ $campaign->description }}
                            </p>
                            <p class="px-2 py-1 mt-1 text-sm bg-green-500 rounded-md w-fit">
                                RM {{ number_format($campaign->price/100,2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <x-flash />
</x-dashboard-panel>

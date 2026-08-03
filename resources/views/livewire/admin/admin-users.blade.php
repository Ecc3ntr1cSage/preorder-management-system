<div class="min-h-[100dvh] bg-paper">
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[0.28em] text-moss">Community directory</p><h1 class="mt-3 font-display text-4xl font-bold tracking-[-0.04em]">Users</h1><p class="mt-2 text-sm text-ink/55">A read-only view of the people making and backing campaigns.</p></div>
            <label class="w-full sm:w-80"><span class="sr-only">Search users</span><input wire:model.live.debounce.300ms="search" type="search" placeholder="Search name or email" class="w-full rounded-xl border-0 bg-white px-4 py-3 text-sm shadow-sm ring-1 ring-ink/10 focus:ring-2 focus:ring-accent"></label>
        </div>

        <div class="overflow-hidden rounded-[2rem] bg-white shadow-[0_20px_60px_rgba(71,52,38,.07)] ring-1 ring-ink/5">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-ink/10 bg-ink/[.03] text-[10px] uppercase tracking-[0.18em] text-ink/45"><tr><th class="px-6 py-4">User</th><th class="px-6 py-4">Campaigns</th><th class="px-6 py-4">Orders</th><th class="px-6 py-4">Joined</th><th class="px-6 py-4">Email</th></tr></thead>
                    <tbody class="divide-y divide-ink/10">
                        @forelse ($users as $user)
                            <tr wire:key="user-{{ $user->id }}" wire:loading.class="opacity-50" class="transition hover:bg-paper/60"><td class="px-6 py-5"><p class="font-semibold">{{ $user->name }}</p><p class="mt-1 text-xs text-ink/45">Member #{{ $user->id }}</p></td><td class="px-6 py-5 font-mono tabular-nums">{{ $user->campaigns_count }}</td><td class="px-6 py-5 font-mono tabular-nums">{{ $user->orders_count }}</td><td class="px-6 py-5 whitespace-nowrap text-ink/55">{{ $user->created_at->format('d M Y') }}</td><td class="px-6 py-5 text-ink/60">{{ $user->email }}</td></tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-14 text-center text-ink/50">No users match that search.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-ink/10 px-6 py-4">{{ $users->links() }}</div>
        </div>
    </div>
</div>

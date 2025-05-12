@can('customer-nav')
    <nav class="bg-gray-800">
        <div class="px-8 mx-auto max-w-7xl">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex items-center flex-1 gap-6">
                    <div class="flex items-center shrink-0">
                        <img src="{{ asset('asset/preorder.png') }}" alt="" class="w-10 -mr-2" />
                    </div>
                    <div class="flex justify-between">
                        <div class="flex space-x-4">
                            <a href="#" class="px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-md"
                                aria-current="page">Shop</a>
                            <a href="#"
                                class="px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">Past
                                Orders</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.show') }}" :active="request() - > routeIs('profile.show')"
                        class="p-2 rounded-full cursor-pointer hover:bg-gray-700" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="white" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </a>
                    <a href="{{ route('profile.show') }}" :active="request() - > routeIs('profile.show')"
                        class="p-2 rounded-full cursor-pointer hover:bg-gray-700" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="white" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </a>
                    <form method="POST" class="mt-auto" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a class="flex items-center p-2 text-white transition rounded-full hover:bg-rose-500/50"
                            href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-rose-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endcan

<div x-cloak
    class="fixed z-50 p-[3px] text-white transition rounded-md top-5 right-5 bg-gradient-to-tr from-sky-400 via-blue-400 to-indigo-400"
    role="alert" x-data="{ shown: false, timeout: null, message: '' }" x-bind:class="shown ? '' : 'opacity-0'"
    x-on:success.window="message = $event.detail.message"; x-init="@this.on('success', ($event) => {
        clearTimeout(timeout);
        shown = true;
        setTimeout(() => { shown = false }, 3000);
    });" x-delay="1000">
    <div class="p-4 bg-gray-700 rounded">
        <span class="inline-block mr-5 text-xl align-middle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.640 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
        </span>
        <span class="inline-block mr-8 text-gray-200 align-middle" x-text="message">
        </span>
    </div>
</div>
<div x-cloak
    class="fixed z-50 p-[3px] text-white transition rounded-md top-5 right-5 bg-gradient-to-tr from-orange-400 via-red-400 to-rose-400"
    role="alert" x-data="{ shown: false, timeout: null, message: '' }" x-bind:class="shown ? '' : 'opacity-0'"
    x-on:error.window="message = $event.detail.message"; x-init="@this.on('error', ($event) => {
        clearTimeout(timeout);
        shown = true;
        setTimeout(() => { shown = false }, 3000);
    });" x-delay="1000">
    <div class="p-4 bg-gray-700 rounded">
        <span class="inline-block mr-5 text-xl align-middle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-rose-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
              </svg>              
        </span>
        <span class="inline-block mr-8 text-gray-200 align-middle" x-text="message">
        </span>
    </div>
</div>
@if (session()->has('message'))
    <div x-cloak
        class="fixed z-50 p-[3px] text-white transition rounded-md top-5 right-5 bg-gradient-to-tr from-sky-400 via-blue-400 to-indigo-400"
        role="alert" x-data="{ open: true }" x-bind:class="open ? '' : 'opacity-0'" x-init="() => { setTimeout(() => { open = false }, 3000); }"
        x-delay="1000">
        <div class="p-4 bg-gray-700 rounded">
            <span class="inline-block mr-5 text-xl align-middle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-indigo-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.640 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </span>
            <span class="inline-block mr-8 text-gray-200 align-middle">
                {{ session('message') }}
            </span>
        </div>
    </div>
@endif

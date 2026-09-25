<nav class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-[#0c0a09]/80 backdrop-blur-md px-4 lg:px-8">
    <div class="flex items-center gap-4">
        <button class="lg:hidden text-gray-500 hover:text-[#09090b] dark:text-gray-400 dark:hover:text-[#f2f2f2]" id="sidebarToggle">
            <i data-lucide="menu" class="h-5 w-5"></i>
        </button>
        <h2 class="hidden lg:block text-lg font-semibold tracking-tight text-[#09090b] dark:text-[#f2f2f2]">
            @yield('page-title', 'Dashboard')
        </h2>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('notifications.index') }}" class="relative text-gray-500 hover:text-[#09090b] dark:text-gray-400 dark:hover:text-[#f2f2f2] transition-colors" title="Notifications">
            <i data-lucide="bell" class="h-5 w-5"></i>
            @php($unreadCount = auth()->user()->unreadNotifications->count())
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </a>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800/50 p-1 pr-2 transition-colors">
                <div class="overflow-hidden rounded-full border border-primary/20 bg-primary/10 flex items-center justify-center" style="width: 32px; height: 32px; flex-shrink: 0;">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="object-contain" style="width: 100%; height: 100%;" />
                </div>
                <span class="hidden md:inline-block text-sm font-medium text-[#09090b] dark:text-[#f2f2f2]">
                    {{ auth()->user()->name }}
                </span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
            </button>
            
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 rounded-md border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0c0a09] py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors">
                    <i data-lucide="user" class="h-4 w-4"></i> Profile
                </a>
                <div class="my-1 h-px bg-gray-200 dark:bg-gray-800"></div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors text-left">
                        <i data-lucide="log-out" class="h-4 w-4"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

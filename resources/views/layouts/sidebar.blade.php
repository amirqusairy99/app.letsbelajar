<aside class="w-64 flex-shrink-0 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-[#0c0a09] transition-transform duration-200 ease-in-out fixed inset-y-0 left-0 z-50 lg:static transform -translate-x-full lg:translate-x-0" id="appSidebar">
    <div class="h-full flex flex-col p-4">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-3 mb-8 px-2 text-[#09090b] dark:text-[#f2f2f2]">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-white">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                </svg>
            </div>
            <span class="font-bold text-lg tracking-tight">LetsBelajar</span>
        </a>

        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Main Menu</p>

        @php
            $currentRouteAssignment = request()->route('assignment');
            $kanbanTarget = is_object($currentRouteAssignment) ? $currentRouteAssignment->id : $currentRouteAssignment;
            $linkClass = "flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-100 hover:text-[#09090b] dark:hover:bg-gray-800/50 dark:hover:text-[#f2f2f2]";
            $activeClass = "bg-gray-100 text-[#09090b] dark:bg-gray-800/50 dark:text-[#f2f2f2]";
            $inactiveClass = "text-gray-600 dark:text-gray-400";
        @endphp

        <nav class="flex flex-col gap-1">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                <i data-lucide="shield" class="h-4 w-4"></i> Admin Panel
            </a>
            <a href="{{ route('admin.users') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.users') ? $activeClass : $inactiveClass }}">
                <i data-lucide="users" class="h-4 w-4"></i> Users
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
            </a>
            <a href="{{ route('assignments.index') }}" class="{{ $linkClass }} {{ request()->routeIs('assignments.*') ? $activeClass : $inactiveClass }}">
                <i data-lucide="book-open" class="h-4 w-4"></i> Assignments
            </a>
            <a href="{{ route('kanban.board') }}" class="{{ $linkClass }} {{ request()->routeIs('kanban.*') ? $activeClass : $inactiveClass }}">
                <i data-lucide="kanban" class="h-4 w-4"></i> Kanban
            </a>
            <a href="{{ route('calendar.index') }}" class="{{ $linkClass }} {{ request()->routeIs('calendar.*') ? $activeClass : $inactiveClass }}">
                <i data-lucide="calendar" class="h-4 w-4"></i> Calendar
            </a>
            <a href="{{ route('notifications.index') }}" class="{{ $linkClass }} {{ request()->routeIs('notifications.*') ? $activeClass : $inactiveClass }}">
                <i data-lucide="bell" class="h-4 w-4"></i> Notifications
            </a>
            @endif
        </nav>

        <p class="mt-8 mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Account</p>

        <nav class="flex flex-col gap-1">
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('profile.edit') }}" class="{{ $linkClass }} {{ request()->routeIs('profile.*') ? $activeClass : $inactiveClass }}">
                <i data-lucide="user-cog" class="h-4 w-4"></i> Settings
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full {{ $linkClass }} {{ $inactiveClass }} text-left">
                    <i data-lucide="log-out" class="h-4 w-4"></i> Log Out
                </button>
            </form>
        </nav>
    </div>
</aside>

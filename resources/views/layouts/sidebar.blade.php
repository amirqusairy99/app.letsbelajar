<aside class="sidebar" id="appSidebar">
    <div class="p-3">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none mb-4 px-2 sidebar-brand">
            <div class="brand-icon-sm">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                </svg>
            </div>
            <span class="fw-bold fs-5" style="color: #F1F5F9; letter-spacing: -0.3px;">AyuhStudy</span>
        </a>

        <p class="text-uppercase small fw-semibold mb-2 px-3" style="color: #475569; font-size: 0.65rem; letter-spacing: 1.5px;">Main Menu</p>

        @php
            $currentRouteAssignment = request()->route('assignment');
            $kanbanTarget = is_object($currentRouteAssignment) ? $currentRouteAssignment->id : $currentRouteAssignment;
        @endphp

        <nav class="nav flex-column gap-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                Dashboard
            </a>
            <a href="{{ route('assignments.index') }}" class="sidebar-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                Assignments
            </a>
            <a href="{{ $kanbanTarget ? route('kanban.index', $kanbanTarget) : route('kanban.board') }}" class="sidebar-link {{ request()->routeIs('kanban.*') ? 'active' : '' }}">
                <i data-lucide="kanban" class="w-4 h-4"></i>
                Kanban Board
            </a>
            <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i data-lucide="bell" class="w-4 h-4"></i>
                Notifications
                @php($sidebarUnread = auth()->user()->unreadNotifications->count())
                @if($sidebarUnread > 0)
                    <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.65rem;">{{ $sidebarUnread }}</span>
                @endif
            </a>
        </nav>

        <p class="text-uppercase small fw-semibold mb-2 px-3 mt-4" style="color: #475569; font-size: 0.65rem; letter-spacing: 1.5px;">Account</p>

        <nav class="nav flex-column gap-1">
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i data-lucide="user-cog" class="w-4 h-4"></i>
                Settings
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Log Out
                </button>
            </form>
        </nav>
    </div>
</aside>

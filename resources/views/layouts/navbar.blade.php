<nav class="topbar navbar navbar-expand-lg border-bottom px-3 px-lg-4">
    <div class="container-fluid">
        <button class="btn btn-link p-0 d-lg-none" id="sidebarToggle" style="color: #94A3B8;">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <div class="d-none d-lg-flex align-items-center">
            <h6 class="mb-0 fw-semibold" style="color: #CBD5E1;">@yield('page-title', 'Dashboard')</h6>
        </div>

        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="{{ route('notifications.index') }}" class="topbar-icon position-relative" title="Notifications">
                <i data-lucide="bell" class="w-5 h-5"></i>
                @php($unreadCount = auth()->user()->unreadNotifications->count())
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown">
                <button class="btn dropdown-toggle d-flex align-items-center gap-2 border-0 topbar-user" type="button" data-bs-toggle="dropdown">
                    <span class="avatar-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item d-flex align-items-center gap-2 py-2">
                            <i data-lucide="user" class="w-4 h-4" style="color: #94A3B8;"></i>
                            Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2">
                                <i data-lucide="log-out" class="w-4 h-4" style="color: #94A3B8;"></i>
                                Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

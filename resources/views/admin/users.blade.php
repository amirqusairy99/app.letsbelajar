@extends('layouts.app')

@section('title', 'Manage Users — Admin')
@section('page-title', 'Users')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">
        Manage Users
    </h1>
    <p class="mb-0" style="color: var(--js-text-muted);">Enable or disable user accounts. Disabled users are shown a payment screen on login.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="color: var(--js-text-muted);">
                    <tr>
                        <th class="px-3 py-3 fw-semibold">Name</th>
                        <th class="px-3 py-3 fw-semibold">Email</th>
                        <th class="px-3 py-3 fw-semibold">Joined</th>
                        <th class="px-3 py-3 fw-semibold">Status</th>
                        <th class="px-3 py-3 fw-semibold text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="mb-0 fw-medium" style="color: var(--js-text-primary);">{{ $user->name }}</p>
                                        @if($user->isAdmin())<span class="badge bg-info" style="font-size: 0.6rem;">Admin</span>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3" style="color: var(--js-text-secondary);">{{ $user->email }}</td>
                            <td class="px-3 py-3" style="color: var(--js-text-muted);">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="px-3 py-3">
                                @if($user->isDisabled())
                                    <span class="badge bg-danger">Disabled</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-end">
                                @if(!$user->isAdmin())
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm {{ $user->isDisabled() ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                        {{ $user->isDisabled() ? 'Enable' : 'Disable' }}
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: var(--js-text-muted);">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-transparent border-0">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Manage Users — Admin')
@section('page-title', 'Users')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold tracking-tight text-foreground mb-1">
        Manage Users
    </h2>
    <p class="text-sm text-muted-foreground">Enable or disable user accounts. Disabled users are shown a payment screen on login.</p>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
    <div class="w-full overflow-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 font-medium">Name</th>
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Joined</th>
                    <th class="px-6 py-4 font-medium text-center">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($users as $user)
                <tr class="hover:bg-muted/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="font-medium text-foreground">{{ $user->name }}</p>
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-600 dark:text-blue-400 mt-0.5">Admin</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">{{ $user->created_at->format('M j, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($user->isDisabled())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-destructive/10 text-destructive">Disabled</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-600 dark:text-green-400">Active</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        @if(!$user->isAdmin())
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline-block m-0">
                                @csrf
                                <button type="submit" class="inline-flex h-8 items-center justify-center rounded-md border text-xs font-medium shadow-sm transition-colors px-3 {{ $user->isDisabled() ? 'border-green-500/30 bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-500/20' : 'border-destructive/30 bg-destructive/10 text-destructive hover:bg-destructive/20' }}">
                                    {{ $user->isDisabled() ? 'Enable' : 'Disable' }}
                                </button>
                            </form>
                        @else
                            <span class="text-muted-foreground text-sm">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-muted-foreground">
                        <i data-lucide="users" class="w-8 h-8 mx-auto mb-3 opacity-50"></i>
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-border bg-muted/20">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

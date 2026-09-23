@extends('layouts.app')

@section('title', 'Manage Users — Admin')
@section('page-title', 'Users')

@section('content')
<div class="mb-4">
 <h2 :text-5xl class="text-2xl font-semibold tracking-tight font-bold mb-1" style=" letter-spacing: -0.5px;">
 Manage Users
 </h2>
 <p class="mb-0" style="">Enable or disable user accounts. Disabled users are shown a payment screen on login.</p>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm">
 <div class="p-6 p-0">
 <div class="w-full overflow-auto">
 <table class="w-full caption-bottom text-sm w-full caption-bottom text-sm-hover align-middle mb-0">
 <thead style="">
 <tr>
 <th class="px-3 py-3 font-semibold">Name</th>
 <th class="px-3 py-3 font-semibold">Email</th>
 <th class="px-3 py-3 font-semibold">Joined</th>
 <th class="px-3 py-3 font-semibold">Status</th>
 <th class="px-3 py-3 font-semibold text-right">Action</th>
 </tr>
 </thead>
 <tbody>
 @forelse($users as $user)
 <tr>
 <td class="px-3 py-3">
 <div class="flex items-center gap-2">
 <span class="avatar-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
 <div>
 <p class="mb-0 font-medium" style="">{{ $user->name }}</p>
 @if($user->isAdmin())<span class="badge bg-info" style="font-size: 0.6rem;">Admin</span>@endif
 </div>
 </div>
 </td>
 <td class="px-3 py-3" style="">{{ $user->email }}</td>
 <td class="px-3 py-3" style="">{{ $user->created_at->format('M j, Y') }}</td>
 <td class="px-3 py-3">
 @if($user->isDisabled())
 <span class="badge bg-danger">Disabled</span>
 @else
 <span class="badge bg-success">Active</span>
 @endif
 </td>
 <td class="px-3 py-3 text-right">
 @if(!$user->isAdmin())
 <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="m-0">
 @csrf
 <button type="submit"
 class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm {{ $user->isDisabled() ? 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-success' : 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-danger' }}">
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
 <td colspan="5" class="text-center py-4" style="">No users found.</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>
 @if($users->hasPages())
 <div class="flex items-center p-6 border-t border-border bg-transparent border-0">
 {{ $users->links() }}
 </div>
 @endif
</div>
@endsection

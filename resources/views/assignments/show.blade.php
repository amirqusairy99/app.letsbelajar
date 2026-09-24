@extends('layouts.app')

@section('title', $assignment->name . ' — LetsBelajar')
@section('page-title', $assignment->name)

@section('content')
<div class="flex justify-between items-start mb-4">
 <div>
 <h1 class="text-2xl font-semibold tracking-tight text-foreground">{{ $assignment->name }}</h1>
 <p class="text-muted-foreground">{{ $assignment->subject }} &middot; {{ $assignment->lecturer_name ?? 'No lecturer' }}</p>
 </div>
 <div class="flex gap-2">
 @can('archive', $assignment)
 @if($assignment->status !== 'archived')
 <form method="POST" action="{{ route('assignments.archive', $assignment) }}">
 @csrf
 <button type="submit" class="inline-flex h-9 items-center justify-center rounded-md border border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-500 px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-amber-500/20">Archive</button>
 </form>
 @else
 <form method="POST" action="{{ route('assignments.unarchive', $assignment) }}">
 @csrf
 <button type="submit" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">Unarchive</button>
 </form>
 @endif
 @endcan
 @can('update', $assignment)
 <a href="{{ route('assignments.edit', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">Edit</a>
 @endcan
 @can('delete', $assignment)
 <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment? This action cannot be undone.');">
 @csrf
 @method('DELETE')
 <button type="submit" class="inline-flex h-9 items-center justify-center rounded-md border border-destructive bg-destructive/10 text-destructive px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-destructive/20">Delete</button>
 </form>
 @endcan
 <a href="{{ route('assignments.analytics', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-cyan-500/30 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-cyan-500/20">Contributions</a>
 <a href="{{ route('tasks.index', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">Tasks</a>
 </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 <div class="lg:col-span-2 flex flex-col gap-6">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow">
 <div class="p-6">
 <h2 class="text-base font-semibold tracking-tight text-foreground mb-2">Description</h2>
 <p class="text-muted-foreground mb-0">{{ $assignment->description ?: 'No description provided.' }}</p>
 </div>
 </div>

 <div class="rounded-xl border border-border bg-card text-card-foreground shadow">
 <div class="p-6">
 <h2 class="text-base font-semibold tracking-tight text-foreground mb-3">Members</h2>
 @foreach($assignment->members as $member)
 <div class="flex justify-between items-center py-2 border-bottom border-border">
 <div class="flex items-center gap-2">
 <span class="avatar text-white rounded-circle flex items-center justify-center" style="width:32px; height:32px; font-size:14px;">{{ substr($member->user->name, 0, 1) }}</span>
 <div>
 <p class="mb-0 text-foreground font-medium">{{ $member->user->name }}</p>
 <p class="text-sm text-muted-foreground">{{ $member->user->email }}</p>
 </div>
 </div>
 <span class="badge bg-{{ $member->role === 'owner' ? 'primary' : 'secondary' }} rounded-pill">{{ ucfirst($member->role) }}</span>
 </div>
 @endforeach

 @can('manageMembers', $assignment)
 <form method="POST" action="{{ route('members.store', $assignment) }}" class="mt-3 pt-3 border-t border-border">
 @csrf
 <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
 <div class="md:col-span-5">
 <input type="email" name="email" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('email') is-invalid @enderror" placeholder="Invite by email">
 @error('email')<div class="text-sm text-destructive mt-1">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-3">
 <select name="role" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2">
 <option value="member">Member</option>
 <option value="owner">Owner</option>
 </select>
 </div>
 <div class="md:col-span-4">
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2 w-full">Invite</button>
 </div>
 </div>
 </form>
 @endcan
 </div>
 </div>
 </div>

 <div class="lg:col-span-1 flex flex-col gap-6">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow">
 <div class="p-6">
 <h2 class="text-base font-semibold tracking-tight text-foreground mb-3">Details</h2>
 <p class="mb-2 text-muted-foreground text-sm">Status</p>
 <p class="mb-3 text-foreground font-medium">{{ ucfirst($assignment->status) }}</p>
 <p class="mb-2 text-muted-foreground text-sm">Due Date</p>
 <p class="mb-3 text-foreground font-medium">{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') : 'No due date' }}</p>
 <p class="mb-2 text-muted-foreground text-sm">Created By</p>
 <p class="mb-0 text-foreground font-medium">{{ $assignment->createdBy->name }}</p>
 </div>
 </div>

 <div class="rounded-xl border border-border bg-card text-card-foreground shadow">
 <div class="p-6">
 <h2 class="text-base font-semibold tracking-tight text-foreground mb-3">Quick Actions</h2>
 <div class="flex flex-col gap-2">
 <a href="{{ route('files.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2 text-left">
 <i data-lucide="folder" class="w-4 h-4 me-2"></i> Files
 </a>
 <a href="{{ route('kanban.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2 text-left">
 <i data-lucide="kanban" class="w-4 h-4 me-2"></i> Kanban Board
 </a>
 <a href="{{ route('assignments.analytics', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2 text-left">
 <i data-lucide="bar-chart-2" class="w-4 h-4 me-2"></i> Member Contributions
 </a>
 </div>
 </div>
 </div>
 </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $assignment->name . ' — LetsBelajar')
@section('page-title', $assignment->name)

@section('content')
<div class="flex justify-between items-start mb-4">
 <div>
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-semibold text-light">{{ $assignment->name }}</text-4xl font-extrabold tracking-tight lg:text-5xl>
 <p class="text-secondary">{{ $assignment->subject }} &middot; {{ $assignment->lecturer_name ?? 'No lecturer' }}</p>
 </div>
 <div class="flex gap-2">
 @can('archive', $assignment)
 <form method="POST" action="{{ route('assignments.archive', $assignment) }}">
 @csrf
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-warning rounded-2">Archive</button>
 </form>
 @endcan
 @can('update', $assignment)
 <a href="{{ route('assignments.edit', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2">Edit</a>
 @endcan
 @can('delete', $assignment)
 <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment? This action cannot be undone.');">
 @csrf
 @method('DELETE')
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-danger rounded-2">Delete</button>
 </form>
 @endcan
 <a href="{{ route('assignments.analytics', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-info rounded-2">Contributions</a>
 <a href="{{ route('tasks.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2">Tasks</a>
 </div>
</div>

<div class="row g-3">
 <div class="col-12 col-lg-8">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3 mb-3">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold text-light mb-2">Description</text-lg font-semibold tracking-tight>
 <p class="text-secondary mb-0">{{ $assignment->description ?: 'No description provided.' }}</p>
 </div>
 </div>

 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold text-light mb-3">Members</text-lg font-semibold tracking-tight>
 @foreach($assignment->members as $member)
 <div class="flex justify-between items-center py-2 border-bottom border-secondary">
 <div class="flex items-center gap-2">
 <span class="avatar text-white rounded-circle flex items-center justify-center" style="width:32px; height:32px; font-size:14px;">{{ substr($member->user->name, 0, 1) }}</span>
 <div>
 <p class="mb-0 text-light font-medium">{{ $member->user->name }}</p>
 <text-sm class="text-secondary">{{ $member->user->email }}</text-sm>
 </div>
 </div>
 <span class="badge bg-{{ $member->role === 'owner' ? 'primary' : 'secondary' }} rounded-pill">{{ ucfirst($member->role) }}</span>
 </div>
 @endforeach

 @can('manageMembers', $assignment)
 <form method="POST" action="{{ route('members.store', $assignment) }}" class="mt-3 pt-3 border-top border-secondary">
 @csrf
 <div class="row g-2">
 <div class="col-12 col-md-5">
 <input type="email" name="email" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('email') is-invalid @enderror" placeholder="Invite by email">
 @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="col-12 col-md-3">
 <select name="role" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2">
 <option value="member">Member</option>
 <option value="owner">Owner</option>
 </select>
 </div>
 <div class="col-12 col-md-4">
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2 w-full">Invite</button>
 </div>
 </div>
 </form>
 @endcan
 </div>
 </div>
 </div>

 <div class="col-12 col-lg-4">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3 mb-3">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold text-light mb-3">Details</text-lg font-semibold tracking-tight>
 <p class="mb-2 text-secondary text-sm">Status</p>
 <p class="mb-3 text-light font-medium">{{ ucfirst($assignment->status) }}</p>
 <p class="mb-2 text-secondary text-sm">Due Date</p>
 <p class="mb-3 text-light font-medium">{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') : 'No due date' }}</p>
 <p class="mb-2 text-secondary text-sm">Created By</p>
 <p class="mb-0 text-light font-medium">{{ $assignment->createdBy->name }}</p>
 </div>
 </div>

 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3">
 <div class="p-6">
 <text-lg font-semibold tracking-tight class="text-base font-semibold tracking-tight font-semibold text-light mb-3">Quick Actions</text-lg font-semibold tracking-tight>
 <div class="d-grid gap-2">
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

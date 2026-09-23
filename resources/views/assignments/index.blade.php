@extends('layouts.app')

@section('title', 'Assignments — LetsBelajar')
@section('page-title', 'Assignments')

@section('content')
<div class="flex justify-between items-center mb-4">
 <div>
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Assignments</text-4xl font-extrabold tracking-tight lg:text-5xl>
 <p class="mb-0 text-sm" style="color: var(--js-text-muted-foreground);">Manage all your group assignments in one place.</p>
 </div>
 <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2" id="new-assignment-inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2">
 <i data-lucide="plus" class="w-4 h-4 me-1"></i> New Assignment
 </a>
</div>

@if($assignments->count() > 0)
<div class="row g-3">
 @foreach($assignments as $assignment)
 <div class="col-12 col-md-6 col-xl-4">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm h-full card-hover">
 <div class="p-6 flex flex-col">
 <div class="flex justify-between items-start mb-3">
 <div>
 <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : 'secondary' }} mb-2">{{ ucfirst($assignment->status) }}</span>
 <text-base font-semibold tracking-tight class="font-semibold mb-1" style="color: var(--js-text-primary);">{{ $assignment->name }}</text-base font-semibold tracking-tight>
 <p class="text-sm mb-0" style="color: var(--js-text-muted-foreground);">{{ $assignment->subject }}</p>
 </div>
 </div>

 <div class="flex gap-6 mb-3" style="font-size: 0.8125rem;">
 @if($assignment->lecturer_name)
 <div class="flex items-center gap-1" style="color: var(--js-text-secondary);">
 <i data-lucide="user" class="w-3 h-3"></i>
 {{ $assignment->lecturer_name }}
 </div>
 @endif
 @if($assignment->due_date)
 <div class="flex items-center gap-1" style="color: var(--js-text-secondary);">
 <i data-lucide="calendar" class="w-3 h-3"></i>
 {{ \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') }}
 </div>
 @endif
 </div>

 <div class="mt-auto">
 <div class="flex justify-between items-center mb-2">
 <div class="flex items-center gap-2">
 <div class="flex" style="margin-left: 0;">
 @foreach($assignment->members->take(3) as $member)
 <span class="avatar" style="width:24px; height:24px; font-size: 0.6rem; margin-left: {{ $loop->first ? '0' : '-6px' }}; border: 2px solid var(--js-bg-surface);" title="{{ $member->user->name }}">
 {{ strtoupper(substr($member->user->name, 0, 1)) }}
 </span>
 @endforeach
 @if($assignment->members->count() > 3)
 <span class="avatar" style="width:24px; height:24px; font-size: 0.55rem; margin-left: -6px; border: 2px solid var(--js-bg-surface); background: var(--js-bg-elevated); color: var(--js-text-secondary);">
 +{{ $assignment->members->count() - 3 }}
 </span>
 @endif
 </div>
 <text-sm style="color: var(--js-text-muted-foreground);">{{ $assignment->tasks_count ?? 0 }} tasks</text-sm>
 </div>
 </div>

 <div class="flex gap-2">
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary flex-1">View</a>
 @can('update', $assignment)
 <a href="{{ route('assignments.edit', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary">
 <i data-lucide="pencil" class="w-3 h-3"></i>
 </a>
 @endcan
 @can('delete', $assignment)
 <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment? This action cannot be undone.');">
 @csrf
 @method('DELETE')
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-danger">
 <i data-lucide="trash-2" class="w-3 h-3"></i>
 </button>
 </form>
 @endcan
 </div>
 </div>
 </div>
 </div>
 </div>
 @endforeach
</div>
@else
<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm">
 <div class="empty-state">
 <div class="empty-state-icon">
 <i data-lucide="book-open" class="w-6 h-6"></i>
 </div>
 <text-base font-semibold tracking-tight class="font-semibold mb-1" style="color: var(--js-text-primary);">No assignments yet</text-base font-semibold tracking-tight>
 <p class="text-sm mb-3" style="color: var(--js-text-muted-foreground);">Create your first assignment to get started.</p>
 <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm">
 <i data-lucide="plus" class="w-4 h-4 me-1"></i> Create Assignment
 </a>
 </div>
</div>
@endif
@endsection

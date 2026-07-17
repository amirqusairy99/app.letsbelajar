@extends('layouts.app')

@section('title', 'Assignments — AyuhStudy')
@section('page-title', 'Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Assignments</h1>
        <p class="mb-0 small" style="color: var(--js-text-muted);">Manage all your group assignments in one place.</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="btn btn-primary" id="new-assignment-btn">
        <i data-lucide="plus" class="w-4 h-4 me-1"></i> New Assignment
    </a>
</div>

@if($assignments->count() > 0)
<div class="row g-3">
    @foreach($assignments as $assignment)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100 card-hover">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : 'secondary' }} mb-2">{{ ucfirst($assignment->status) }}</span>
                        <h6 class="fw-semibold mb-1" style="color: var(--js-text-primary);">{{ $assignment->name }}</h6>
                        <p class="small mb-0" style="color: var(--js-text-muted);">{{ $assignment->subject }}</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3" style="font-size: 0.8125rem;">
                    @if($assignment->lecturer_name)
                    <div class="d-flex align-items-center gap-1" style="color: var(--js-text-secondary);">
                        <i data-lucide="user" class="w-3 h-3"></i>
                        {{ $assignment->lecturer_name }}
                    </div>
                    @endif
                    @if($assignment->due_date)
                    <div class="d-flex align-items-center gap-1" style="color: var(--js-text-secondary);">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        {{ \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') }}
                    </div>
                    @endif
                </div>

                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex" style="margin-left: 0;">
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
                            <small style="color: var(--js-text-muted);">{{ $assignment->tasks_count ?? 0 }} tasks</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary flex-grow-1">View</a>
                        @can('update', $assignment)
                            <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-sm btn-outline-secondary">
                                <i data-lucide="pencil" class="w-3 h-3"></i>
                            </a>
                        @endcan
                        @can('delete', $assignment)
                            <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
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
<div class="card border-0 shadow-sm">
    <div class="empty-state">
        <div class="empty-state-icon">
            <i data-lucide="book-open" class="w-6 h-6"></i>
        </div>
        <h6 class="fw-semibold mb-1" style="color: var(--js-text-primary);">No assignments yet</h6>
        <p class="small mb-3" style="color: var(--js-text-muted);">Create your first assignment to get started.</p>
        <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="w-4 h-4 me-1"></i> Create Assignment
        </a>
    </div>
</div>
@endif
@endsection

@extends('layouts.app')

@section('title', $assignment->name . ' — LetsBelajar')
@section('page-title', $assignment->name)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 fw-semibold text-light">{{ $assignment->name }}</h1>
        <p class="text-secondary">{{ $assignment->subject }} &middot; {{ $assignment->lecturer_name ?? 'No lecturer' }}</p>
    </div>
    <div class="d-flex gap-2">
        @can('archive', $assignment)
            <form method="POST" action="{{ route('assignments.archive', $assignment) }}">
                @csrf
                <button type="submit" class="btn btn-outline-warning rounded-2">Archive</button>
            </form>
        @endcan
        @can('update', $assignment)
            <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-outline-primary rounded-2">Edit</a>
        @endcan
        @can('delete', $assignment)
            <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-2">Delete</button>
            </form>
        @endcan
        <a href="{{ route('assignments.analytics', $assignment) }}" class="btn btn-outline-info rounded-2">Contributions</a>
        <a href="{{ route('tasks.index', $assignment) }}" class="btn btn-primary rounded-2">Tasks</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">
                <h5 class="h6 fw-semibold text-light mb-2">Description</h5>
                <p class="text-secondary mb-0">{{ $assignment->description ?: 'No description provided.' }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h5 class="h6 fw-semibold text-light mb-3">Members</h5>
                @foreach($assignment->members as $member)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px; height:32px; font-size:14px;">{{ substr($member->user->name, 0, 1) }}</span>
                            <div>
                                <p class="mb-0 text-light fw-medium">{{ $member->user->name }}</p>
                                <small class="text-secondary">{{ $member->user->email }}</small>
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
                                <input type="email" name="email" class="form-control rounded-2 @error('email') is-invalid @enderror" placeholder="Invite by email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <select name="role" class="form-select rounded-2">
                                    <option value="member">Member</option>
                                    <option value="owner">Owner</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary rounded-2 w-100">Invite</button>
                            </div>
                        </div>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">
                <h5 class="h6 fw-semibold text-light mb-3">Details</h5>
                <p class="mb-2 text-secondary small">Status</p>
                <p class="mb-3 text-light fw-medium">{{ ucfirst($assignment->status) }}</p>
                <p class="mb-2 text-secondary small">Due Date</p>
                <p class="mb-3 text-light fw-medium">{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') : 'No due date' }}</p>
                <p class="mb-2 text-secondary small">Created By</p>
                <p class="mb-0 text-light fw-medium">{{ $assignment->createdBy->name }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h5 class="h6 fw-semibold text-light mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('files.index', $assignment) }}" class="btn btn-outline-secondary rounded-2 text-start">
                        <i data-lucide="folder" class="w-4 h-4 me-2"></i> Files
                    </a>
                    <a href="{{ route('kanban.index', $assignment) }}" class="btn btn-outline-secondary rounded-2 text-start">
                        <i data-lucide="kanban" class="w-4 h-4 me-2"></i> Kanban Board
                    </a>
                    <a href="{{ route('assignments.analytics', $assignment) }}" class="btn btn-outline-secondary rounded-2 text-start">
                        <i data-lucide="bar-chart-2" class="w-4 h-4 me-2"></i> Member Contributions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

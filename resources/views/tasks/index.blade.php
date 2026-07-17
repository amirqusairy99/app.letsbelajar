@extends('layouts.app')

@section('title', 'Tasks — AyuhStudy')
@section('page-title', 'Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-semibold text-light">Tasks - {{ $assignment->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('kanban.index', $assignment) }}" class="btn btn-outline-primary rounded-2">
            <i data-lucide="kanban" class="w-4 h-4 me-1"></i> Kanban
        </a>
        @if($assignment->members->where('user_id', auth()->id())->first()?->role === 'owner')
            <button type="button" class="btn btn-primary rounded-2" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                <i data-lucide="plus" class="w-4 h-4 me-1"></i> New Task
            </button>
        @endif
        <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-secondary rounded-2">Back</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Title</th>
                    <th>Assigned To</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td class="ps-4">{{ $task->title }}</td>
                        <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                        <td>
                            <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning text-dark' : 'secondary') }} rounded-pill">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'doing' ? 'primary' : 'secondary') }} rounded-pill">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : '-' }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('tasks.edit', [$assignment, $task]) }}" class="btn btn-sm btn-outline-primary rounded-2">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-light" id="newTaskModalLabel">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.store', $assignment) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label text-light">Title</label>
                        <input type="text" id="title" name="title" class="form-control rounded-2 @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label text-light">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control rounded-2 @error('description') is-invalid @enderror"></textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label text-light">Assigned To</label>
                        <select id="assigned_to" name="assigned_to" class="form-select rounded-2 @error('assigned_to') is-invalid @enderror">
                            <option value="">Unassigned</option>
                            @foreach($assignment->members as $member)
                                <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="due_date" class="form-label text-light">Due Date</label>
                            <input type="date" id="due_date" name="due_date" class="form-control rounded-2 @error('due_date') is-invalid @enderror">
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="priority" class="form-label text-light">Priority</label>
                            <select id="priority" name="priority" class="form-select rounded-2 @error('priority') is-invalid @enderror">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="status" class="form-label text-light">Status</label>
                        <select id="status" name="status" class="form-select rounded-2 @error('status') is-invalid @enderror">
                            <option value="todo">To Do</option>
                            <option value="doing">Doing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary rounded-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-2">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

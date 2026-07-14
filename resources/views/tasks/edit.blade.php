@extends('layouts.app')

@section('title', 'Edit Task — JomStudy')
@section('page-title', 'Edit Task')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('tasks.index', $assignment) }}" class="btn btn-link text-secondary p-0 me-3">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <h1 class="h3 fw-semibold text-light">Edit Task</h1>
</div>

<div class="card border-0 shadow-sm rounded-3" style="max-width: 700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.update', [$assignment, $task]) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label for="title" class="form-label text-light">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" class="form-control rounded-2 @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="description" class="form-label text-light">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-control rounded-2 @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="assigned_to" class="form-label text-light">Assigned To</label>
                    <select id="assigned_to" name="assigned_to" class="form-select rounded-2 @error('assigned_to') is-invalid @enderror">
                        <option value="">Unassigned</option>
                        @foreach($assignment->members as $member)
                            <option value="{{ $member->user_id }}" {{ old('assigned_to', $task->assigned_to) == $member->user_id ? 'selected' : '' }}>
                                {{ $member->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="due_date" class="form-label text-light">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date) }}" class="form-control rounded-2 @error('due_date') is-invalid @enderror">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="priority" class="form-label text-light">Priority</label>
                    <select id="priority" name="priority" class="form-select rounded-2 @error('priority') is-invalid @enderror">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="status" class="form-label text-light">Status</label>
                    <select id="status" name="status" class="form-select rounded-2 @error('status') is-invalid @enderror">
                        <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="doing" {{ old('status', $task->status) === 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary rounded-2">Update Task</button>
                    <a href="{{ route('tasks.index', $assignment) }}" class="btn btn-outline-secondary rounded-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tasks — LetsBelajar')
@section('page-title', 'Tasks')

@section('content')
<div class="flex justify-between items-center mb-4">
 <h2 class="text-2xl font-semibold tracking-tight text-foreground">Tasks - {{ $assignment->name }}</h2>
 <div class="flex gap-2">
 <a href="{{ route('kanban.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2">
 <i data-lucide="kanban" class="w-4 h-4 me-1"></i> Kanban
 </a>
 @if($assignment->members->where('user_id', auth()->id())->first()?->role === 'owner')
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2" data-bs-toggle="modal" data-bs-target="#newTaskModal">
 <i data-lucide="plus" class="w-4 h-4 me-1"></i> New Task
 </button>
 @endif
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2">Back</a>
 </div>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3">
 <div class="w-full overflow-auto">
 <table class="w-full caption-bottom text-sm w-full caption-bottom text-sm-hover mb-0 align-middle">
 <thead class="bg-light">
 <tr>
 <th class="ps-4">Title</th>
 <th>Assigned To</th>
 <th>Priority</th>
 <th>Status</th>
 <th>Due Date</th>
 <th class="text-right pe-4">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($tasks as $task)
 <tr>
 <td class="ps-4">{{ $task->title }}</td>
 <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
 <td>
 <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning text-foreground' : 'secondary') }} rounded-pill">
 {{ ucfirst($task->priority) }}
 </span>
 </td>
 <td>
 <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'doing' ? 'primary' : 'secondary') }} rounded-pill">
 {{ ucfirst($task->status) }}
 </span>
 </td>
 <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : '-' }}</td>
 <td class="text-right pe-4">
 <a href="{{ route('tasks.edit', [$assignment, $task]) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2">Edit</a>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="6" class="text-center py-4 text-muted-foreground">No tasks found.</td>
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
 <div class="modal-title text-foreground" id="newTaskModalLabel">New Task</div>
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <form method="POST" action="{{ route('tasks.store', $assignment) }}">
 @csrf
 <div class="modal-body">
 <div class="mb-3">
 <label for="title" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Title</label>
 <input type="text" id="title" name="title" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('title') is-invalid @enderror" required>
 @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="description" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Description</label>
 <textarea id="description" name="description" rows="3" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('description') is-invalid @enderror"></textarea>
 @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="mb-3">
 <label for="assigned_to" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Assigned To</label>
 <select id="assigned_to" name="assigned_to" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('assigned_to') is-invalid @enderror">
 <option value="">Unassigned</option>
 @foreach($assignment->members as $member)
 <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
 @endforeach
 </select>
 @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
 <div class="md:col-span-6">
 <label for="due_date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Due Date</label>
 <input type="date" id="due_date" name="due_date" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('due_date') is-invalid @enderror">
 @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-6">
 <label for="priority" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Priority</label>
 <select id="priority" name="priority" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('priority') is-invalid @enderror">
 <option value="low">Low</option>
 <option value="medium">Medium</option>
 <option value="high">High</option>
 </select>
 </div>
 </div>
 <div class="mt-3">
 <label for="status" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Status</label>
 <select id="status" name="status" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('status') is-invalid @enderror">
 <option value="todo">To Do</option>
 <option value="doing">Doing</option>
 <option value="completed">Completed</option>
 </select>
 </div>
 </div>
 <div class="modal-footer border-secondary">
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2" data-bs-dismiss="modal">Cancel</button>
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2">Create Task</button>
 </div>
 </form>
 </div>
 </div>
</div>
@endsection

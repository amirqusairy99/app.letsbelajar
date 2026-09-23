@extends('layouts.app')

@section('title', 'Edit Task — LetsBelajar')
@section('page-title', 'Edit Task')

@section('content')
<div class="flex items-center mb-4">
 <a href="{{ route('tasks.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-link text-muted-foreground p-0 me-3">
 <i data-lucide="arrow-left" class="w-5 h-5"></i>
 </a>
 <h2 :text-5xl class="text-2xl font-semibold tracking-tight font-semibold text-foreground">Edit Task</h2>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3" style="max-width: 700px;">
 <div class="p-6">
 <form method="POST" action="{{ route('tasks.update', [$assignment, $task]) }}">
 @csrf
 @method('PUT')
 <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
 <div class="md:col-span-12">
 <label for="title" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Title</label>
 <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('title') is-invalid @enderror" required>
 @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-12">
 <label for="description" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Description</label>
 <textarea id="description" name="description" rows="4" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
 @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-6">
 <label for="assigned_to" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Assigned To</label>
 <select id="assigned_to" name="assigned_to" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('assigned_to') is-invalid @enderror">
 <option value="">Unassigned</option>
 @foreach($assignment->members as $member)
 <option value="{{ $member->user_id }}" {{ old('assigned_to', $task->assigned_to) == $member->user_id ? 'selected' : '' }}>
 {{ $member->user->name }}
 </option>
 @endforeach
 </select>
 @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-6">
 <label for="due_date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Due Date</label>
 <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date) }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('due_date') is-invalid @enderror">
 @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-6">
 <label for="priority" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Priority</label>
 <select id="priority" name="priority" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('priority') is-invalid @enderror">
 <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
 <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
 <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
 </select>
 @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-6">
 <label for="status" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-foreground">Status</label>
 <select id="status" name="status" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 rounded-2 @error('status') is-invalid @enderror">
 <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>To Do</option>
 <option value="doing" {{ old('status', $task->status) === 'doing' ? 'selected' : '' }}>Doing</option>
 <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
 </select>
 @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
 </div>
 <div class="md:col-span-12 flex gap-2 pt-2">
 <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 rounded-2">Update Task</button>
 <a href="{{ route('tasks.index', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2">Cancel</a>
 </div>
 </div>
 </form>
 </div>
</div>
@endsection

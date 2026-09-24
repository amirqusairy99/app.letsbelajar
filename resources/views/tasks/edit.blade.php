@extends('layouts.app')

@section('title', 'Edit Task — LetsBelajar')
@section('page-title', 'Edit Task')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('tasks.index', $assignment) }}" class="mr-4 inline-flex h-9 w-9 items-center justify-center rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Edit Task</h1>
    </div>
</div>

<div class="rounded-xl border border-border bg-card shadow-sm max-w-3xl w-full overflow-hidden">
    <div class="p-6 md:p-8">
        <form method="POST" action="{{ route('tasks.update', [$assignment, $task]) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 md:col-span-2">
                    <label for="title" class="text-sm font-medium leading-none text-foreground">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50" required>
                    @error('title')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2 md:col-span-2">
                    <label for="description" class="text-sm font-medium leading-none text-foreground">Description</label>
                    <textarea id="description" name="description" rows="4" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="assigned_to" class="text-sm font-medium leading-none text-foreground">Assigned To</label>
                    <select id="assigned_to" name="assigned_to" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="">Unassigned</option>
                        @foreach($assignment->members as $member)
                        <option value="{{ $member->user_id }}" {{ old('assigned_to', $task->assigned_to) == $member->user_id ? 'selected' : '' }}>
                            {{ $member->user->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="due_date" class="text-sm font-medium leading-none text-foreground">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                    @error('due_date')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="priority" class="text-sm font-medium leading-none text-foreground">Priority</label>
                    <select id="priority" name="priority" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="status" class="text-sm font-medium leading-none text-foreground">Status</label>
                    <select id="status" name="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="doing" {{ old('status', $task->status) === 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="pt-6 flex justify-end gap-3 border-t border-border mt-8">
                <a href="{{ route('tasks.index', $assignment) }}" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
                    Cancel
                </a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-8 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                    Update Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

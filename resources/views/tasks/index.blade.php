@extends('layouts.app')

@section('title', 'Tasks — LetsBelajar')
@section('page-title', 'Tasks')

@section('content')
<div x-data="{ showNewTaskModal: false }">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold tracking-tight text-foreground">Tasks - {{ $assignment->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('kanban.index', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
                <i data-lucide="kanban" class="w-4 h-4 me-1"></i> Kanban
            </a>
            @if($assignment->members->where('user_id', auth()->id())->first()?->role === 'owner')
            <button type="button" @click="showNewTaskModal = true" class="inline-flex h-9 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                <i data-lucide="plus" class="w-4 h-4 me-1"></i> New Task
            </button>
            @endif
            <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">Back</a>
        </div>
    </div>

    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="w-full overflow-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3 font-medium">Title</th>
                        <th class="px-4 py-3 font-medium">Assigned To</th>
                        <th class="px-4 py-3 font-medium">Priority</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Due Date</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-3 font-medium">{{ $task->title }}</td>
                        <td class="px-4 py-3">{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->priority === 'high' ? 'bg-destructive/10 text-destructive' : ($task->priority === 'medium' ? 'bg-amber-500/10 text-amber-500 dark:text-amber-400' : 'bg-secondary text-secondary-foreground') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->status === 'completed' ? 'bg-green-500/10 text-green-600 dark:text-green-400' : ($task->status === 'doing' ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400' : 'bg-secondary text-secondary-foreground') }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('tasks.edit', [$assignment, $task]) }}" class="inline-flex h-8 items-center justify-center rounded-md border border-input bg-background px-3 text-xs font-medium transition-colors hover:bg-accent hover:text-accent-foreground">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-muted-foreground">No tasks found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showNewTaskModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showNewTaskModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/50 transition-opacity" 
                 @click="showNewTaskModal = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showNewTaskModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-card text-card-foreground rounded-xl border border-border text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <div class="px-6 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="text-lg font-medium" id="modal-title">New Task</h3>
                    <button type="button" @click="showNewTaskModal = false" class="text-muted-foreground hover:text-foreground transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('tasks.store', $assignment) }}">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label for="title" class="text-sm font-medium leading-none text-foreground">Title</label>
                            <input type="text" id="title" name="title" class="mt-1.5 flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 @error('title') border-destructive @enderror" required>
                            @error('title')<p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="description" class="text-sm font-medium leading-none text-foreground">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1.5 flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 @error('description') border-destructive @enderror"></textarea>
                            @error('description')<p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="assigned_to" class="text-sm font-medium leading-none text-foreground">Assigned To</label>
                            <select id="assigned_to" name="assigned_to" class="mt-1.5 flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 @error('assigned_to') border-destructive @enderror">
                                <option value="">Unassigned</option>
                                @foreach($assignment->members as $member)
                                <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')<p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="due_date" class="text-sm font-medium leading-none text-foreground">Due Date</label>
                                <input type="date" id="due_date" name="due_date" class="mt-1.5 flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 @error('due_date') border-destructive @enderror">
                                @error('due_date')<p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="priority" class="text-sm font-medium leading-none text-foreground">Priority</label>
                                <select id="priority" name="priority" class="mt-1.5 flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 @error('priority') border-destructive @enderror">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="status" class="text-sm font-medium leading-none text-foreground">Status</label>
                            <select id="status" name="status" class="mt-1.5 flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50 @error('status') border-destructive @enderror">
                                <option value="todo">To Do</option>
                                <option value="doing">Doing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-border bg-muted/20 flex justify-end gap-3">
                        <button type="button" @click="showNewTaskModal = false" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">Cancel</button>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-6 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Assignment — LetsBelajar')
@section('page-title', 'Edit Assignment')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('assignments.show', $assignment) }}" class="mr-4 inline-flex h-9 w-9 items-center justify-center rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground transition-colors">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Edit Assignment</h1>
        <p class="text-sm text-muted-foreground mt-1">Update details for {{ $assignment->name }}</p>
    </div>
</div>

<div class="rounded-xl border border-border bg-card shadow-sm max-w-5xl w-full overflow-hidden">
    <div class="p-6 md:p-8">
        <form method="POST" action="{{ route('assignments.update', $assignment) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Subject -->
                <div class="space-y-2 md:col-span-2">
                    <label for="subject" class="text-sm font-medium leading-none text-foreground">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $assignment->subject) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50" required>
                    @error('subject')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assignment Name -->
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="text-sm font-medium leading-none text-foreground">Assignment Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $assignment->name) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50" required>
                    @error('name')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lecturer Name -->
                <div class="space-y-2 md:col-span-2">
                    <label for="lecturer_name" class="text-sm font-medium leading-none text-foreground">Lecturer Name</label>
                    <input type="text" id="lecturer_name" name="lecturer_name" value="{{ old('lecturer_name', $assignment->lecturer_name) }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                    @error('lecturer_name')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div class="space-y-2">
                    <label for="due_date" class="text-sm font-medium leading-none text-foreground">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d') : '') }}" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                    @error('due_date')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label for="status" class="text-sm font-medium leading-none text-foreground">Status</label>
                    <select id="status" name="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="active" {{ old('status', $assignment->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ old('status', $assignment->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="space-y-2 md:col-span-2">
                    <label for="description" class="text-sm font-medium leading-none text-foreground">Description</label>
                    <textarea id="description" name="description" rows="5" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-50">{{ old('description', $assignment->description) }}</textarea>
                    @error('description')
                        <p class="text-sm font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 flex justify-end gap-3 border-t border-border mt-8">
                <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
                    Cancel
                </a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-8 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                    Update Assignment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

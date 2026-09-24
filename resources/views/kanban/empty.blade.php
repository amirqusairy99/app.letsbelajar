@extends('layouts.app')

@section('title', 'Kanban Board — LetsBelajar')
@section('page-title', 'Kanban Board')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] py-12 px-4">
    <div class="rounded-2xl border border-border bg-card text-card-foreground shadow-lg text-center p-8 sm:p-12 w-full max-w-lg">
        
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-primary/10 text-primary shadow-inner">
            <i data-lucide="kanban" class="h-10 w-10"></i>
        </div>
        
        <h2 class="text-2xl font-bold tracking-tight text-foreground mb-3">Create Your First Assignment</h2>
        <p class="mb-8 text-muted-foreground mx-auto max-w-sm text-sm leading-relaxed">
            You haven't created or joined any assignments yet. Set up your first assignment to start tracking tasks on the Kanban board.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <a href="{{ route('assignments.create') }}" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-6 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 w-full sm:w-auto">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Assignment
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-6 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground w-full sm:w-auto">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Dashboard
            </a>
        </div>
        
    </div>
</div>
@endsection

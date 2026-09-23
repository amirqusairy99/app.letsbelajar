@extends('layouts.app')

@section('title', 'Kanban Board — LetsBelajar')
@section('page-title', 'Kanban Board')

@section('content')
<div class="container mx-auto px-4 md:px-8 flex flex-col items-center justify-center" style="min-height: 70vh;">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-lg text-center p-4 p-md-5 w-full" style="max-width: 500px; background-color: var(--js-bg-surface); border-radius: var(--js-radius);">
 <div class="empty-state p-0">
 <div class="empty-state-icon mx-auto mb-4" style="width: 72px; height: 72px; border-radius: 18px; background: var(--js-accent-muted); color: var(--js-accent-hover); display: flex; align-items: center; justify-content: center;">
 <i data-lucide="kanban" style="width: 32px; height: 32px;"></i>
 </div>
 <div class="font-bold mb-2" style=" letter-spacing: -0.5px;">Create Your First Assignment</div>
 <p class="mb-4 text-muted-foreground mx-auto" style="max-width: 360px; font-size: 0.9rem; line-height: 1.6; ">
 You haven't created or joined any assignments yet. Set up your first assignment to start tracking tasks on the Kanban board.
 </p>
 <div class="flex flex-col flex-sm-row gap-2 justify-center items-center">
 <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 px-4 py-2 w-full w-sm-auto">
 <i data-lucide="plus" class="w-4 h-4 me-1"></i> Create Assignment
 </a>
 <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 px-4 py-2 w-full w-sm-auto">
 <i data-lucide="arrow-left" class="w-4 h-4 me-1"></i> Back to Dashboard
 </a>
 </div>
 </div>
 </div>
</div>
@endsection

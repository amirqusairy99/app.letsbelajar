@extends('layouts.app')

@section('title', 'Kanban Board — LetsBelajar')
@section('page-title', 'Kanban Board')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="card border-0 shadow-lg text-center p-4 p-md-5 w-100" style="max-width: 500px; background-color: var(--js-bg-surface); border-radius: var(--js-radius);">
        <div class="empty-state p-0">
            <div class="empty-state-icon mx-auto mb-4" style="width: 72px; height: 72px; border-radius: 18px; background: var(--js-accent-muted); color: var(--js-accent-hover); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="kanban" style="width: 32px; height: 32px;"></i>
            </div>
            <h4 class="fw-bold mb-2" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Create Your First Assignment</h4>
            <p class="mb-4 text-secondary mx-auto" style="max-width: 360px; font-size: 0.9rem; line-height: 1.6; color: var(--js-text-muted);">
                You haven't created or joined any assignments yet. Set up your first assignment to start tracking tasks on the Kanban board.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center align-items-center">
                <a href="{{ route('assignments.create') }}" class="btn btn-primary px-4 py-2 w-100 w-sm-auto">
                    <i data-lucide="plus" class="w-4 h-4 me-1"></i> Create Assignment
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2 w-100 w-sm-auto">
                    <i data-lucide="arrow-left" class="w-4 h-4 me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

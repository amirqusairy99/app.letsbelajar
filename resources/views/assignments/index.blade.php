@extends('layouts.app')

@section('title', 'Assignments — LetsBelajar')
@section('page-title', 'Assignments')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-foreground">Assignments</h1>
        <p class="text-sm text-muted-foreground mt-1">Manage all your group assignments in one place.</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 h-9">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Assignment
    </a>
</div>

@php
    $activeAssignments = $assignments->where('status', 'active');
    $archivedAssignments = $assignments->where('status', 'archived');
@endphp

@if($activeAssignments->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
    @foreach($activeAssignments as $assignment)
    <div class="group relative flex flex-col rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/50">
        <div class="p-6 flex flex-col flex-1">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mb-3 bg-green-500/10 text-green-600 dark:text-green-400 ring-1 ring-inset ring-green-500/20">
                        Active
                    </span>
                    <h3 class="font-semibold text-lg leading-none tracking-tight mb-1 text-foreground">{{ $assignment->name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ $assignment->subject }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-2 mb-6 mt-2 text-sm text-muted-foreground">
                @if($assignment->lecturer_name)
                <div class="flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 opacity-70"></i>
                    <span>{{ $assignment->lecturer_name }}</span>
                </div>
                @endif
                @if($assignment->due_date)
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 opacity-70"></i>
                    <span>{{ \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') }}</span>
                </div>
                @endif
            </div>

            <div class="mt-auto pt-4 border-t border-border/50">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach($assignment->members->take(3) as $member)
                            <span class="inline-block h-6 w-6 rounded-full ring-2 ring-background bg-primary/20 flex items-center justify-center text-[10px] font-bold text-primary" title="{{ $member->user->name }}">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </span>
                            @endforeach
                            @if($assignment->members->count() > 3)
                            <span class="inline-block h-6 w-6 rounded-full ring-2 ring-background bg-muted flex items-center justify-center text-[10px] font-medium text-muted-foreground">
                                +{{ $assignment->members->count() - 3 }}
                            </span>
                            @endif
                        </div>
                        <span class="text-xs text-muted-foreground">{{ $assignment->tasks_count ?? 0 }} tasks</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex flex-1 items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary/10 text-primary hover:bg-primary/20 h-9 px-4">View</a>
                    @can('update', $assignment)
                    <a href="{{ route('assignments.edit', $assignment) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </a>
                    @endcan
                    @can('delete', $assignment)
                    <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-destructive/20 text-destructive hover:bg-destructive hover:text-destructive-foreground h-9 w-9">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm p-12 text-center mb-12">
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 mb-4">
        <i data-lucide="book-open" class="h-6 w-6 text-primary"></i>
    </div>
    <h3 class="text-lg font-semibold text-foreground mb-1">No active assignments yet</h3>
    <p class="text-sm text-muted-foreground mb-4">Create your first assignment to get started.</p>
    <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 h-9">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Assignment
    </a>
</div>
@endif

@if($archivedAssignments->count() > 0)
<div class="mt-8">
    <hr class="mb-10 border-border border-dashed border-t-2">
    <h2 class="text-xl font-bold tracking-tight text-foreground mb-6">Archived Assignments</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-75 hover:opacity-100 transition-opacity">
        @foreach($archivedAssignments as $assignment)
        <div class="group relative flex flex-col rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/50">
            <div class="p-6 flex flex-col flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mb-3 bg-gray-500/10 text-gray-600 dark:text-gray-400 ring-1 ring-inset ring-gray-500/20">
                            Archived
                        </span>
                        <h3 class="font-semibold text-lg leading-none tracking-tight mb-1 text-foreground">{{ $assignment->name }}</h3>
                        <p class="text-sm text-muted-foreground">{{ $assignment->subject }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mb-6 mt-2 text-sm text-muted-foreground">
                    @if($assignment->lecturer_name)
                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 opacity-70"></i>
                        <span>{{ $assignment->lecturer_name }}</span>
                    </div>
                    @endif
                    @if($assignment->due_date)
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 opacity-70"></i>
                        <span>{{ \Carbon\Carbon::parse($assignment->due_date)->format('M j, Y') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-auto pt-4 border-t border-border/50">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2 overflow-hidden">
                                @foreach($assignment->members->take(3) as $member)
                                <span class="inline-block h-6 w-6 rounded-full ring-2 ring-background bg-primary/20 flex items-center justify-center text-[10px] font-bold text-primary" title="{{ $member->user->name }}">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </span>
                                @endforeach
                                @if($assignment->members->count() > 3)
                                <span class="inline-block h-6 w-6 rounded-full ring-2 ring-background bg-muted flex items-center justify-center text-[10px] font-medium text-muted-foreground">
                                    +{{ $assignment->members->count() - 3 }}
                                </span>
                                @endif
                            </div>
                            <span class="text-xs text-muted-foreground">{{ $assignment->tasks_count ?? 0 }} tasks</span>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex flex-1 items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary/10 text-primary hover:bg-primary/20 h-9 px-4">View</a>
                        @can('update', $assignment)
                        <a href="{{ route('assignments.edit', $assignment) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        @endcan
                        @can('delete', $assignment)
                        <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Are you sure you want to delete this assignment?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-destructive/20 text-destructive hover:bg-destructive hover:text-destructive-foreground h-9 w-9">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

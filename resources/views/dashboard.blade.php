@extends('layouts.app')

@section('title', 'Dashboard — LetsBelajar')
@section('page-title', 'Dashboard')

@section('content')
{{-- Greeting --}}
<div class="mb-8 flex flex-col md:flex-row justify-between md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-foreground mb-2">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
        </h1>
        <p class="text-base text-muted-foreground">Here's what's happening with your assignments today.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-all hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 h-10">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Assignment
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Pending Card -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-card p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-amber-500/30 hover:-translate-y-1">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-500/10 blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-500 shadow-inner">
                <i data-lucide="clock" class="h-7 w-7"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">Pending</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $pendingTasks }}</p>
            </div>
        </div>
    </div>
    
    <!-- Completed Card -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-card p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-green-500/30 hover:-translate-y-1">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-green-500/10 blur-2xl group-hover:bg-green-500/20 transition-all duration-500"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-500/10 text-green-600 dark:text-green-500 shadow-inner">
                <i data-lucide="check-circle" class="h-7 w-7"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">Completed</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $completedTasks }}</p>
            </div>
        </div>
    </div>

    <!-- Active Assignments Card -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-card p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-blue-500/30 hover:-translate-y-1">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-500 shadow-inner">
                <i data-lucide="book-open" class="h-7 w-7"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">Assignments</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $myAssignments->where('status', 'active')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Overdue Card -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-card p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-red-500/30 hover:-translate-y-1">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-red-500/10 blur-2xl group-hover:bg-red-500/20 transition-all duration-500"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-red-500/10 text-red-600 dark:text-red-500 shadow-inner">
                <i data-lucide="alert-circle" class="h-7 w-7"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">Overdue</p>
                <p class="text-3xl font-black text-foreground mt-1">{{ $overdueTasks->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Upcoming Deadlines (Main Col) --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight text-foreground flex items-center gap-2">
                <i data-lucide="calendar-clock" class="h-5 w-5 text-primary"></i>
                Upcoming Deadlines
            </h2>
            <a href="{{ route('calendar.index') }}" class="text-sm font-medium text-primary hover:underline">View Calendar</a>
        </div>
        
        <div class="rounded-2xl border border-border bg-card shadow-sm overflow-hidden">
            @if($upcomingDeadlines->count() > 0)
                <div class="divide-y divide-border">
                    @foreach($upcomingDeadlines as $task)
                        <div class="p-5 hover:bg-muted/30 transition-colors flex items-center justify-between gap-4 group">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <i data-lucide="calendar" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-foreground group-hover:text-primary transition-colors">{{ $task->title }}</p>
                                    <p class="text-xs text-muted-foreground flex items-center gap-1 mt-1">
                                        <i data-lucide="book" class="h-3 w-3"></i> {{ $task->assignment->name ?? 'No Assignment' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ 
                                    $task->priority === 'high' ? 'bg-red-500/10 text-red-600 ring-red-500/20' : 
                                    ($task->priority === 'medium' ? 'bg-amber-500/10 text-amber-600 ring-amber-500/20' : 
                                    'bg-blue-500/10 text-blue-600 ring-blue-500/20') 
                                }}">
                                    {{ ucfirst($task->priority) }} Priority
                                </span>
                                <span class="text-xs font-medium {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-destructive' : 'text-muted-foreground' }}">
                                    {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center p-10 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-muted mb-4">
                        <i data-lucide="check" class="h-8 w-8 text-muted-foreground"></i>
                    </div>
                    <p class="text-lg font-semibold text-foreground">You're all caught up!</p>
                    <p class="text-sm text-muted-foreground">No upcoming tasks or deadlines.</p>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Recent Activity (Sidebar Col) --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight text-foreground flex items-center gap-2">
                <i data-lucide="activity" class="h-5 w-5 text-primary"></i>
                Recent Activity
            </h2>
        </div>
        
        <div class="rounded-2xl border border-border bg-card shadow-sm p-6">
            @if($recentActivities->count() > 0)
                <div class="space-y-6">
                    @foreach($recentActivities as $activity)
                        <div class="relative flex gap-4">
                            @if(!$loop->last)
                                <div class="absolute left-[11px] top-8 h-full w-[2px] bg-border"></div>
                            @endif
                            <div class="relative z-10 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/20 ring-4 ring-card">
                                <div class="h-2 w-2 rounded-full bg-primary"></div>
                            </div>
                            <div class="flex-1 pb-1">
                                <p class="text-sm text-foreground">
                                    <span class="font-semibold">{{ $activity->user->name ?? 'Someone' }}</span> 
                                    {{ $activity->description }}
                                </p>
                                <p class="text-xs text-muted-foreground mt-1">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-muted-foreground text-center py-8">No recent activity to show.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard — LetsBelajar')
@section('page-title', 'Dashboard')

@section('content')
{{-- Greeting --}}
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
    </h1>
    <p class="mb-0" style="color: var(--js-text-muted);">Here's what's happening with your assignments today.</p>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-warning">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-warning">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Pending</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $pendingTasks }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-success">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-success">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Completed</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $completedTasks }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-accent">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-accent">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Assignments</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $myAssignments->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-info">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-danger">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Overdue</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $overdueTasks->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Main column --}}
    <div class="col-12 col-lg-8">
        {{-- Upcoming Deadlines --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="h6 fw-semibold mb-0" style="color: var(--js-text-primary);">
                        <i data-lucide="calendar" class="w-4 h-4 me-2" style="color: var(--js-accent-hover); vertical-align: -2px;"></i>
                        Upcoming Deadlines
                    </h5>
                    <span class="badge bg-secondary">{{ $upcomingDeadlines->count() }}</span>
                </div>
                @forelse($upcomingDeadlines as $task)
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--js-border) !important;">
                        <div>
                            <p class="mb-0 fw-medium" style="color: var(--js-text-primary); font-size: 0.875rem;">{{ $task->title }}</p>
                            <small style="color: var(--js-text-muted);">{{ $task->assignment->name }}</small>
                        </div>
                        <div class="text-end d-flex align-items-center gap-2">
                            <small style="color: var(--js-text-secondary);">{{ \Carbon\Carbon::parse($task->due_date)->format('M j') }}</small>
                            <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary') }}">{{ ucfirst($task->priority) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i data-lucide="calendar-check" class="w-5 h-5 mb-2" style="color: var(--js-text-muted);"></i>
                        <p class="mb-0 small" style="color: var(--js-text-muted);">No upcoming deadlines. You're all caught up!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Deadline Reminders --}}
        @if($tasksDueToday->count() > 0 || $tasksDueTomorrow->count() > 0 || $overdueTasks->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="h6 fw-semibold mb-3" style="color: var(--js-text-primary);">
                    <i data-lucide="alarm-clock" class="w-4 h-4 me-2" style="color: var(--js-warning); vertical-align: -2px;"></i>
                    Deadline Reminders
                </h5>
                @if($overdueTasks->count() > 0)
                    <p class="small text-uppercase fw-bold mb-2" style="color: var(--js-danger); font-size: 0.65rem; letter-spacing: 1px;">Overdue</p>
                    @foreach($overdueTasks as $task)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last || $tasksDueToday->count() > 0 || $tasksDueTomorrow->count() > 0 ? 'border-bottom' : '' }}" style="border-color: var(--js-border) !important;">
                            <p class="mb-0 small" style="color: #FCA5A5;">{{ $task->title }}</p>
                            <span class="badge bg-danger">Overdue</span>
                        </div>
                    @endforeach
                @endif
                @if($tasksDueToday->count() > 0)
                    <p class="small text-uppercase fw-bold mb-2 {{ $overdueTasks->count() > 0 ? 'mt-3' : '' }}" style="color: var(--js-warning); font-size: 0.65rem; letter-spacing: 1px;">Due Today</p>
                    @foreach($tasksDueToday as $task)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last || $tasksDueTomorrow->count() > 0 ? 'border-bottom' : '' }}" style="border-color: var(--js-border) !important;">
                            <p class="mb-0 small" style="color: var(--js-text-primary);">{{ $task->title }}</p>
                            <span class="badge bg-warning">Today</span>
                        </div>
                    @endforeach
                @endif
                @if($tasksDueTomorrow->count() > 0)
                    <p class="small text-uppercase fw-bold mb-2 {{ $overdueTasks->count() > 0 || $tasksDueToday->count() > 0 ? 'mt-3' : '' }}" style="color: var(--js-info); font-size: 0.65rem; letter-spacing: 1px;">Due Tomorrow</p>
                    @foreach($tasksDueTomorrow as $task)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--js-border) !important;">
                            <p class="mb-0 small" style="color: var(--js-text-primary);">{{ $task->title }}</p>
                            <span class="badge bg-info">Tomorrow</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar column --}}
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="h6 fw-semibold mb-3" style="color: var(--js-text-primary);">
                    <i data-lucide="activity" class="w-4 h-4 me-2" style="color: var(--js-success); vertical-align: -2px;"></i>
                    Recent Activity
                </h5>
                @forelse($recentActivities as $activity)
                    <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--js-border) !important;">
                        <div class="flex-shrink-0 mt-1">
                            <div style="width: 6px; height: 6px; border-radius: 50%; background: var(--js-accent); margin-top: 4px;"></div>
                        </div>
                        <div>
                            <p class="mb-0 small" style="color: var(--js-text-primary);">{{ $activity->description }}</p>
                            <small style="color: var(--js-text-muted);">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <i data-lucide="activity" class="w-5 h-5 mb-2" style="color: var(--js-text-muted);"></i>
                        <p class="mb-0 small" style="color: var(--js-text-muted);">No recent activity yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

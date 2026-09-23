@extends('layouts.app')

@section('title', 'Dashboard — LetsBelajar')
@section('page-title', 'Dashboard')

@section('content')
{{-- Greeting --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-[#09090b] dark:text-[#f2f2f2] mb-1">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}
    </h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Here's what's happening with your assignments today.</p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] p-6 shadow-sm flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-500">
            <i data-lucide="clock" class="h-6 w-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-[#09090b] dark:text-[#f2f2f2]">{{ $pendingTasks }}</p>
        </div>
    </div>
    
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] p-6 shadow-sm flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-500">
            <i data-lucide="check-circle" class="h-6 w-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
            <p class="text-2xl font-bold text-[#09090b] dark:text-[#f2f2f2]">{{ $completedTasks }}</p>
        </div>
    </div>
    
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] p-6 shadow-sm flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-primary/20">
            <i data-lucide="book-open" class="h-6 w-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Assignments</p>
            <p class="text-2xl font-bold text-[#09090b] dark:text-[#f2f2f2]">{{ $myAssignments->count() }}</p>
        </div>
    </div>
    
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] p-6 shadow-sm flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-500">
            <i data-lucide="alert-triangle" class="h-6 w-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue</p>
            <p class="text-2xl font-bold text-[#09090b] dark:text-[#f2f2f2]">{{ $overdueTasks->count() }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main column --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Upcoming Deadlines --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] shadow-sm">
            <div class="border-b border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-[#09090b] dark:text-[#f2f2f2] flex items-center gap-2">
                        <i data-lucide="calendar" class="h-5 w-5 text-primary"></i>
                        Upcoming Deadlines
                    </h2>
                    <span class="inline-flex items-center justify-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                        {{ $upcomingDeadlines->count() }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                @forelse($upcomingDeadlines as $task)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-800' : '' }}">
                        <div>
                            <p class="text-sm font-medium text-[#09090b] dark:text-[#f2f2f2]">{{ $task->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->assignment->name }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($task->due_date)->format('M j') }}
                            </span>
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $task->priority === 'high' ? 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-900/50' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-400 dark:ring-amber-900/50' : 'bg-gray-50 text-gray-600 ring-gray-500/10 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700/50') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <i data-lucide="calendar-check" class="h-10 w-10 text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No upcoming deadlines. You're all caught up!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Deadline Reminders --}}
        @if($tasksDueToday->count() > 0 || $tasksDueTomorrow->count() > 0 || $overdueTasks->count() > 0)
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] shadow-sm">
            <div class="border-b border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#09090b] dark:text-[#f2f2f2] flex items-center gap-2">
                    <i data-lucide="alarm-clock" class="h-5 w-5 text-amber-500"></i>
                    Deadline Reminders
                </h2>
            </div>
            
            <div class="p-6">
                @if($overdueTasks->count() > 0)
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-red-600 dark:text-red-400">Overdue</p>
                    @foreach($overdueTasks as $task)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last || $tasksDueToday->count() > 0 || $tasksDueTomorrow->count() > 0 ? 'border-b border-gray-200 dark:border-gray-800' : '' }}">
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $task->title }}</p>
                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-900/50">Overdue</span>
                        </div>
                    @endforeach
                @endif
                
                @if($tasksDueToday->count() > 0)
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-500 {{ $overdueTasks->count() > 0 ? 'mt-6' : '' }}">Due Today</p>
                    @foreach($tasksDueToday as $task)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last || $tasksDueTomorrow->count() > 0 ? 'border-b border-gray-200 dark:border-gray-800' : '' }}">
                            <p class="text-sm font-medium text-[#09090b] dark:text-[#f2f2f2]">{{ $task->title }}</p>
                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-400 dark:ring-amber-900/50">Today</span>
                        </div>
                    @endforeach
                @endif
                
                @if($tasksDueTomorrow->count() > 0)
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 {{ $overdueTasks->count() > 0 || $tasksDueToday->count() > 0 ? 'mt-6' : '' }}">Due Tomorrow</p>
                    @foreach($tasksDueTomorrow as $task)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-800' : '' }}">
                            <p class="text-sm font-medium text-[#09090b] dark:text-[#f2f2f2]">{{ $task->title }}</p>
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-900/50">Tomorrow</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar column --}}
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-[#0c0a09] shadow-sm">
            <div class="border-b border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#09090b] dark:text-[#f2f2f2] flex items-center gap-2">
                    <i data-lucide="activity" class="h-5 w-5 text-primary"></i>
                    Recent Activity
                </h2>
            </div>
            
            <div class="p-6">
                @forelse($recentActivities as $activity)
                    <div class="flex gap-4 py-3 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-800' : '' }}">
                        <div class="mt-1 flex-shrink-0">
                            <div class="h-2 w-2 rounded-full bg-primary ring-4 ring-primary/20"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#09090b] dark:text-[#f2f2f2]">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <i data-lucide="activity" class="h-8 w-8 text-gray-300 dark:text-gray-600 mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Contributions: ' . $assignment->name . ' — LetsBelajar')
@section('page-title', 'Member Contributions')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-semibold tracking-tight text-foreground mb-1">Member Contributions</h2>
        <p class="text-sm text-muted-foreground">Workspace: <strong class="text-foreground font-medium">{{ $assignment->name }}</strong> &middot; {{ $assignment->subject }}</p>
    </div>
    <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground shadow-sm">
        <i data-lucide="arrow-left" class="w-4 h-4 me-2"></i>Back to Workspace
    </a>
</div>

{{-- Overall Stats Banner --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="rounded-xl border border-indigo-500/20 bg-indigo-500/5 text-card-foreground shadow-sm overflow-hidden">
        <div class="p-6 flex items-center gap-4">
            <div class="bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-full p-3 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider block">Completion Rate</span>
                <span class="text-2xl font-bold tracking-tight text-foreground block mt-1">
                    {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%
                </span>
                <span class="text-xs text-muted-foreground block mt-1">{{ $completedTasks }}/{{ $totalTasks }} Tasks Done</span>
            </div>
        </div>
    </div>
    
    <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 text-card-foreground shadow-sm overflow-hidden">
        <div class="p-6 flex items-center gap-4">
            <div class="bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full p-3 flex items-center justify-center shrink-0">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider block">Total Files</span>
                <span class="text-2xl font-bold tracking-tight text-foreground block mt-1">{{ $totalFiles }}</span>
                <span class="text-xs text-muted-foreground block mt-1">Uploaded documents</span>
            </div>
        </div>
    </div>
    
    <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 text-card-foreground shadow-sm overflow-hidden">
        <div class="p-6 flex items-center gap-4">
            <div class="bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-full p-3 flex items-center justify-center shrink-0">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider block">Total Logs</span>
                <span class="text-2xl font-bold tracking-tight text-foreground block mt-1">{{ $totalActivities }}</span>
                <span class="text-xs text-muted-foreground block mt-1">Recorded actions</span>
            </div>
        </div>
    </div>
    
    <div class="rounded-xl border border-blue-500/20 bg-blue-500/5 text-card-foreground shadow-sm overflow-hidden">
        <div class="p-6 flex items-center gap-4">
            <div class="bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-full p-3 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider block">Total Members</span>
                <span class="text-2xl font-bold tracking-tight text-foreground block mt-1">{{ count($memberStats) }}</span>
                <span class="text-xs text-muted-foreground block mt-1">Collaborating students</span>
            </div>
        </div>
    </div>
</div>

{{-- Member Contribution Grid --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm mb-8 overflow-hidden">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-6 border-b border-border gap-4">
        <div class="text-lg font-semibold tracking-tight text-foreground">Member Contributions</div>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary text-secondary-foreground">Sorted by activity count</span>
    </div>
    <div class="w-full overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border">
                <tr>
                    <th class="px-6 py-4 font-medium">Member</th>
                    <th class="px-4 py-4 font-medium">Role</th>
                    <th class="px-4 py-4 font-medium text-center">Files Uploaded</th>
                    <th class="px-4 py-4 font-medium text-center">Tasks Created</th>
                    <th class="px-4 py-4 font-medium text-center">Tasks Assigned (Done)</th>
                    <th class="px-4 py-4 font-medium text-center">Total Activities</th>
                    <th class="px-6 py-4 font-medium">Task Completion Progress</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($memberStats as $stat)
                <tr class="hover:bg-muted/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary/10 text-primary font-bold text-sm shrink-0">
                                {{ strtoupper(substr($stat['user']->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="font-medium text-foreground">{{ $stat['user']->name }}</p>
                                <p class="text-xs text-muted-foreground mt-0.5">{{ $stat['user']->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] uppercase tracking-wide font-medium {{ $stat['role'] === 'owner' ? 'bg-primary text-primary-foreground' : 'bg-secondary text-secondary-foreground' }}">
                            {{ $stat['role'] }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center font-medium text-foreground">{{ $stat['files_uploaded'] }}</td>
                    <td class="px-4 py-4 text-center font-medium text-foreground">{{ $stat['tasks_created'] }}</td>
                    <td class="px-4 py-4 text-center whitespace-nowrap">
                        <span class="font-medium text-green-600 dark:text-green-400">{{ $stat['tasks_completed'] }}</span>
                        <span class="text-muted-foreground mx-1">/</span>
                        <span class="text-muted-foreground">{{ $stat['tasks_assigned'] }}</span>
                    </td>
                    <td class="px-4 py-4 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $stat['activities_count'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $percent = $stat['tasks_assigned'] > 0 ? round(($stat['tasks_completed'] / $stat['tasks_assigned']) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-3 min-w-[140px] max-w-[200px]">
                            <div class="w-full bg-secondary rounded-full h-2 overflow-hidden border border-border/50">
                                <div class="bg-green-500 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-muted-foreground w-9 text-right shrink-0">{{ $percent }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Action Timelines per Member --}}
<div class="mb-4">
    <div class="text-lg font-semibold tracking-tight text-foreground mb-4">Recent Actions by Member</div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($memberStats as $stat)
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm flex flex-col overflow-hidden max-h-[400px]">
            <div class="flex items-center gap-3 p-4 border-b border-border bg-muted/20">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">
                    {{ strtoupper(substr($stat['user']->name, 0, 1)) }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-foreground truncate">{{ $stat['user']->name }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-secondary text-secondary-foreground shrink-0 border border-border">
                    {{ $stat['activities_count'] }} actions
                </span>
            </div>
            <div class="p-5 flex-1 overflow-y-auto">
                <div class="space-y-4 relative before:absolute before:inset-0 before:ml-1.5 before:-translate-x-px before:h-full before:w-0.5 before:bg-border">
                    @forelse($stat['recent_activities'] as $activity)
                    <div class="relative flex items-start gap-4">
                        <div class="mt-1.5 flex items-center justify-center w-3 h-3 rounded-full border-2 border-card bg-primary shrink-0 shadow-sm relative z-10"></div>
                        <div class="flex-1 p-3 rounded-lg border border-border bg-background shadow-sm hover:border-primary/30 transition-colors">
                            <p class="text-xs text-foreground leading-relaxed">{{ $activity->description }}</p>
                            <p class="text-[10px] font-medium text-muted-foreground mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 relative z-10 bg-card rounded-lg border border-dashed border-border">
                        <i data-lucide="activity" class="w-8 h-8 mx-auto text-muted-foreground/30 mb-2"></i>
                        <p class="text-xs text-muted-foreground">No recent activity.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Contributions: ' . $assignment->name . ' — LetsBelajar')
@section('page-title', 'Member Contributions')

@section('content')
<div class="flex justify-between items-center mb-4">
 <div>
 <h2 :text-5xl class="text-2xl font-semibold tracking-tight font-bold mb-1" style=" letter-spacing: -0.5px;">Member Contributions</h2>
 <p class="mb-0 text-sm" style="">Workspace: <strong class="text-foreground">{{ $assignment->name }}</strong> &middot; {{ $assignment->subject }}</p>
 </div>
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 rounded-2">
 <i data-lucide="arrow-left" class="w-4 h-4 me-1" style="vertical-align: -2px;"></i>Back to Workspace
 </a>
</div>

{{-- Overall Stats Banner --}}
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-4">
 <div class="md:col-span-3">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow h-full position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0.03) 100%); border-color: rgba(99, 102, 241, 0.25) !important;">
 <div class="p-6 flex items-center gap-6">
 <div class="stat-icon bg-primary text-white shadow-sm rounded-full p-3 flex items-center justify-center">
 <i data-lucide="check-circle" class="w-5 h-5"></i>
 </div>
 <div>
 <span class="text-muted-foreground text-sm block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Completion Rate</span>
 <span class="text-xl font-semibold tracking-tight mb-0 font-bold text-foreground">
 {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%
 </span>
 <div class="text-muted-foreground block mt-1" style="font-size: 0.7rem;">{{ $completedTasks }}/{{ $totalTasks }} Tasks Done</div>
 </div>
 </div>
 </div>
 </div>
 <div class="md:col-span-3">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow h-full position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.03) 100%); border-color: rgba(16, 185, 129, 0.25) !important;">
 <div class="p-6 flex items-center gap-6">
 <div class="stat-icon bg-success text-white shadow-sm rounded-full p-3 flex items-center justify-center">
 <i data-lucide="file-text" class="w-5 h-5"></i>
 </div>
 <div>
 <span class="text-muted-foreground text-sm block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Files</span>
 <span class="text-xl font-semibold tracking-tight mb-0 font-bold text-foreground">{{ $totalFiles }}</span>
 <div class="text-muted-foreground block mt-1" style="font-size: 0.7rem;">Uploaded documents</div>
 </div>
 </div>
 </div>
 </div>
 <div class="md:col-span-3">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow h-full position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.03) 100%); border-color: rgba(245, 158, 11, 0.25) !important;">
 <div class="p-6 flex items-center gap-6">
 <div class="stat-icon bg-warning text-white shadow-sm rounded-full p-3 flex items-center justify-center">
 <i data-lucide="activity" class="w-5 h-5"></i>
 </div>
 <div>
 <span class="text-muted-foreground text-sm block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Logs</span>
 <span class="text-xl font-semibold tracking-tight mb-0 font-bold text-foreground">{{ $totalActivities }}</span>
 <div class="text-muted-foreground block mt-1" style="font-size: 0.7rem;">Recorded actions</div>
 </div>
 </div>
 </div>
 </div>
 <div class="md:col-span-3">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow h-full position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.03) 100%); border-color: rgba(59, 130, 246, 0.25) !important;">
 <div class="p-6 flex items-center gap-6">
 <div class="stat-icon bg-info text-white shadow-sm rounded-full p-3 flex items-center justify-center">
 <i data-lucide="users" class="w-5 h-5"></i>
 </div>
 <div>
 <span class="text-muted-foreground text-sm block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Members</span>
 <span class="text-xl font-semibold tracking-tight mb-0 font-bold text-foreground">{{ count($memberStats) }}</span>
 <div class="text-muted-foreground block mt-1" style="font-size: 0.7rem;">Collaborating students</div>
 </div>
 </div>
 </div>
 </div>
</div>

{{-- Member Contribution Grid --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow mb-4">
 <div class="flex flex-col space-y-1.5 p-6 border-b border-border py-3 flex items-center justify-between">
 <div class="text-base font-semibold tracking-tight font-semibold mb-0 text-foreground">Member Contributions</div>
 <span class="badge bg-secondary">Sorted by activity count</span>
 </div>
 <div class="p-6 p-0">
 <div class="w-full overflow-auto">
 <table class="w-full caption-bottom text-sm w-full caption-bottom text-sm-hover mb-0 align-middle">
 <thead>
 <tr>
 <th class="ps-4">Member</th>
 <th>Role</th>
 <th class="text-center">Files Uploaded</th>
 <th class="text-center">Tasks Created</th>
 <th class="text-center">Tasks Assigned (Done)</th>
 <th class="text-center">Total Activities</th>
 <th class="pe-4">Task Completion Progress</th>
 </tr>
 </thead>
 <tbody>
 @foreach($memberStats as $stat)
 <tr>
 <td class="ps-4">
 <div class="flex items-center gap-6">
 <span class="avatar text-white rounded-circle flex items-center justify-center" style="width:36px; height:36px; font-size:16px; font-weight: 600; ">
 {{ substr($stat['user']->name, 0, 1) }}
 </span>
 <div>
 <p class="mb-0 font-semibold text-foreground">{{ $stat['user']->name }}</p>
 <div class="text-muted-foreground" style="font-size: 0.75rem;">{{ $stat['user']->email }}</div>
 </div>
 </div>
 </td>
 <td>
 <span class="badge bg-{{ $stat['role'] === 'owner' ? 'primary' : 'secondary' }} rounded-pill px-2">
 {{ ucfirst($stat['role']) }}
 </span>
 </td>
 <td class="text-center font-semibold text-foreground">{{ $stat['files_uploaded'] }}</td>
 <td class="text-center font-semibold text-foreground">{{ $stat['tasks_created'] }}</td>
 <td class="text-center">
 <span class="font-semibold text-green-600 dark:text-green-400">{{ $stat['tasks_completed'] }}</span>
 <span class="text-muted-foreground">/</span>
 <span class="text-muted-foreground text-sm">{{ $stat['tasks_assigned'] }}</span>
 </td>
 <td class="text-center font-semibold text-blue-600 dark:text-blue-400">{{ $stat['activities_count'] }}</td>
 <td class="pe-4">
 @php
 $percent = $stat['tasks_assigned'] > 0 ? round(($stat['tasks_completed'] / $stat['tasks_assigned']) * 100) : 0;
 @endphp
 <div class="flex items-center gap-2" style="min-width: 140px;">
 <div class="progress flex-1 bg-dark rounded-pill" style="height: 6px; border: 1px solid rgba(255,255,255,0.05);">
 <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
 </div>
 <span class="text-sm font-semibold text-muted-foreground" style="min-width: 35px; font-size: 0.8rem;">{{ $percent }}%</span>
 </div>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>
</div>

{{-- Recent Action Timelines per Member --}}
<div class="mb-4">
 <div class="text-lg font-semibold tracking-tight font-bold mb-3 text-foreground" style="letter-spacing: -0.3px;">Recent Actions by Member</div>
 <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
 @foreach($memberStats as $stat)
 <div class="md:col-span-6">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow h-full">
 <div class="flex flex-col space-y-1.5 p-6 border-b border-border py-3 flex items-center gap-2">
 <span class="avatar text-white rounded-circle flex items-center justify-center" style="width:28px; height:28px; font-size:12px; font-weight: 600; background: linear-gradient(135deg, #475569, #64748B);">
 {{ substr($stat['user']->name, 0, 1) }}
 </span>
 <div class="text-base font-semibold tracking-tight font-semibold mb-0 text-foreground">{{ $stat['user']->name }}</div>
 <span class="badge bg-secondary ms-auto">{{ $stat['activities_count'] }} actions</span>
 </div>
 <div class="p-6 py-2">
 <div class="space-y-2">
 @forelse($stat['recent_activities'] as $activity)
 <div class="flex gap-2 py-2 border-bottom last:border-0" style="border-color: var(--js-border) !important;">
 <div class="mt-1 flex-shrink-0">
 <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--js-accent, #6366F1); margin-top: 5px;"></div>
 </div>
 <div class="flex-1">
 <p class="mb-0 text-sm text-foreground">{{ $activity->description }}</p>
 <div class="text-muted-foreground" style="font-size: 0.72rem;">{{ $activity->created_at->diffForHumans() }}</div>
 </div>
 </div>
 @empty
 <div class="text-center py-4 text-muted-foreground text-sm">
 <i data-lucide="activity-square" class="w-8 h-8 mb-2 opacity-50" style=""></i>
 <p class="mb-0 text-muted-foreground">No recent activity recorded.</p>
 </div>
 @endforelse
 </div>
 </div>
 </div>
 </div>
 @endforeach
 </div>
</div>
@endsection

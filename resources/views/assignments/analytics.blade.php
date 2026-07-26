@extends('layouts.app')

@section('title', 'Contributions: ' . $assignment->name . ' — LetsBelajar')
@section('page-title', 'Member Contributions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Member Contributions</h1>
        <p class="mb-0 small" style="color: var(--js-text-muted);">Workspace: <strong class="text-light">{{ $assignment->name }}</strong> &middot; {{ $assignment->subject }}</p>
    </div>
    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-secondary rounded-2">
        <i data-lucide="arrow-left" class="w-4 h-4 me-1" style="vertical-align: -2px;"></i>Back to Workspace
    </a>
</div>

{{-- Overall Stats Banner --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0.03) 100%); border-color: rgba(99, 102, 241, 0.25) !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary text-white shadow-sm" style="background: linear-gradient(135deg, var(--js-accent), #8B5CF6) !important;">
                    <i data-lucide="check-square" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Completion Rate</span>
                    <span class="h4 mb-0 fw-bold text-light">
                        {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%
                    </span>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">{{ $completedTasks }}/{{ $totalTasks }} Tasks Done</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.03) 100%); border-color: rgba(16, 185, 129, 0.25) !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success text-white shadow-sm" style="background: linear-gradient(135deg, var(--js-success), #10B981) !important;">
                    <i data-lucide="files" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Files</span>
                    <span class="h4 mb-0 fw-bold text-light">{{ $totalFiles }}</span>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">Uploaded documents</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.03) 100%); border-color: rgba(245, 158, 11, 0.25) !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning text-white shadow-sm" style="background: linear-gradient(135deg, var(--js-warning), #F59E0B) !important;">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Logs</span>
                    <span class="h4 mb-0 fw-bold text-light">{{ $totalActivities }}</span>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">Recorded actions</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.03) 100%); border-color: rgba(59, 130, 246, 0.25) !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info text-white shadow-sm" style="background: linear-gradient(135deg, var(--js-info), #3B82F6) !important;">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Members</span>
                    <span class="h4 mb-0 fw-bold text-light">{{ count($memberStats) }}</span>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">Collaborating students</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Member Contribution Grid --}}
<div class="card mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h5 class="h6 fw-semibold mb-0 text-light">Member Contributions</h5>
        <span class="badge bg-secondary">Sorted by activity count</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
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
                                <div class="d-flex align-items-center gap-3">
                                    <span class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px; font-size:16px; font-weight: 600; background: linear-gradient(135deg, var(--js-accent), #8B5CF6);">
                                        {{ substr($stat['user']->name, 0, 1) }}
                                    </span>
                                    <div>
                                        <p class="mb-0 fw-semibold text-light">{{ $stat['user']->name }}</p>
                                        <small class="text-secondary" style="font-size: 0.75rem;">{{ $stat['user']->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $stat['role'] === 'owner' ? 'primary' : 'secondary' }} rounded-pill px-2">
                                    {{ ucfirst($stat['role']) }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold text-light">{{ $stat['files_uploaded'] }}</td>
                            <td class="text-center fw-semibold text-light">{{ $stat['tasks_created'] }}</td>
                            <td class="text-center">
                                <span class="fw-semibold text-success">{{ $stat['tasks_completed'] }}</span>
                                <span class="text-secondary">/</span>
                                <span class="text-secondary small">{{ $stat['tasks_assigned'] }}</span>
                            </td>
                            <td class="text-center fw-semibold text-info">{{ $stat['activities_count'] }}</td>
                            <td class="pe-4">
                                @php
                                    $percent = $stat['tasks_assigned'] > 0 ? round(($stat['tasks_completed'] / $stat['tasks_assigned']) * 100) : 0;
                                @endphp
                                <div class="d-flex align-items-center gap-2" style="min-width: 140px;">
                                    <div class="progress flex-grow-1 bg-dark rounded-pill" style="height: 6px; border: 1px solid rgba(255,255,255,0.05);">
                                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="small fw-semibold text-secondary" style="min-width: 35px; font-size: 0.8rem;">{{ $percent }}%</span>
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
    <h2 class="h5 fw-bold mb-3 text-light" style="letter-spacing: -0.3px;">Recent Actions by Member</h2>
    <div class="row g-3">
        @foreach($memberStats as $stat)
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header py-3 d-flex align-items-center gap-2">
                        <span class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width:28px; height:28px; font-size:12px; font-weight: 600; background: linear-gradient(135deg, #475569, #64748B);">
                            {{ substr($stat['user']->name, 0, 1) }}
                        </span>
                        <h5 class="h6 fw-semibold mb-0 text-light">{{ $stat['user']->name }}</h5>
                        <span class="badge bg-secondary ms-auto">{{ $stat['activities_count'] }} actions</span>
                    </div>
                    <div class="card-body py-2">
                        <div class="space-y-2">
                            @forelse($stat['recent_activities'] as $activity)
                                <div class="d-flex gap-2 py-2 border-bottom last:border-0" style="border-color: var(--js-border) !important;">
                                    <div class="mt-1 flex-shrink-0">
                                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--js-accent, #6366F1); margin-top: 5px;"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small text-light">{{ $activity->description }}</p>
                                        <small class="text-secondary" style="font-size: 0.72rem;">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-secondary small">
                                    <i data-lucide="activity-square" class="w-8 h-8 mb-2 opacity-50" style="color: var(--js-text-muted);"></i>
                                    <p class="mb-0 text-secondary">No recent activity recorded.</p>
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

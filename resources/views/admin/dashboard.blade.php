@extends('layouts.app')

@section('title', 'Admin Dashboard — LetsBelajar')
@section('page-title', 'Admin Dashboard')

@php
    $storageUsed = $storageUsedBytes;
    if ($storageUsed >= 1073741824) {
        $storageUsedLabel = number_format($storageUsed / 1073741824, 2) . ' GB';
    } elseif ($storageUsed >= 1048576) {
        $storageUsedLabel = number_format($storageUsed / 1048576, 2) . ' MB';
    } elseif ($storageUsed >= 1024) {
        $storageUsedLabel = number_format($storageUsed / 1024, 2) . ' KB';
    } else {
        $storageUsedLabel = number_format($storageUsed, 0) . ' B';
    }
@endphp

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">
        Platform Overview
    </h1>
    <p class="mb-0" style="color: var(--js-text-muted);">A quick summary of activity across LetsBelajar.</p>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-info">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-info">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Total Users</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $totalUsers }}</p>
                    <small style="color: var(--js-text-muted);">+{{ $newUsersToday }} today / +{{ $newUsersThisWeek }} week</small>
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
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Workspaces / Classes</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $totalWorkspaces }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-success">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-success">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Total Assignments</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $totalAssignments }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 stat-card stat-warning">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-warning">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Active Users Today</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $activeUsersToday }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Storage + file uploads --}}
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon icon-danger">
                    <i data-lucide="hard-drive" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="mb-0 small fw-medium" style="color: var(--js-text-muted);">Storage Used</p>
                    <p class="h3 mb-0 fw-bold" style="color: var(--js-text-primary);">{{ $storageUsedLabel }}</p>
                    <small style="color: var(--js-text-muted);">{{ $totalFileUploads }} total uploads</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent activity feed --}}
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="h6 fw-semibold mb-3" style="color: var(--js-text-primary);">
                    <i data-lucide="activity" class="w-4 h-4 me-2" style="color: var(--js-success); vertical-align: -2px;"></i>
                    Recent Activity
                </h5>
                <div class="overflow-y-auto pe-1" style="max-height: 350px;">
                    @forelse($recentActivity as $activity)
                        <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--js-border) !important;">
                            <div class="flex-shrink-0 mt-1">
                                <div style="width: 6px; height: 6px; border-radius: 50%; background: var(--js-accent); margin-top: 4px;"></div>
                            </div>
                            <div>
                                <p class="mb-0 small" style="color: var(--js-text-primary);">
                                    @if($activity->user)
                                        <span class="fw-semibold">{{ $activity->user->name }}</span>
                                    @endif
                                    {{ $activity->description }}
                                </p>
                                <small style="color: var(--js-text-muted);">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i data-lucide="activity" class="w-5 h-5 mb-2" style="color: var(--js-text-muted);"></i>
                            <p class="mb-0 small" style="color: var(--js-text-muted);">No activity recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
<div class="mb-8">
    <h1 class="text-3xl font-bold tracking-tight mb-2">
        Platform Overview
    </h1>
    <p class="text-muted-foreground">A quick summary of activity across LetsBelajar.</p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Total Users --}}
    <div class="bg-card text-card-foreground rounded-xl border shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-primary/10 text-primary rounded-lg">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Total Users</p>
                <h3 class="text-2xl font-bold">{{ $totalUsers }}</h3>
            </div>
        </div>
        <div class="mt-4 text-xs text-muted-foreground">
            <span class="text-primary font-medium">+{{ $newUsersToday }}</span> today / <span class="text-primary font-medium">+{{ $newUsersThisWeek }}</span> this week
        </div>
    </div>

    {{-- Workspaces --}}
    <div class="bg-card text-card-foreground rounded-xl border shadow-sm p-6 flex flex-col justify-center">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-500/10 text-indigo-500 rounded-lg">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Workspaces</p>
                <h3 class="text-2xl font-bold">{{ $totalWorkspaces }}</h3>
            </div>
        </div>
    </div>

    {{-- Assignments --}}
    <div class="bg-card text-card-foreground rounded-xl border shadow-sm p-6 flex flex-col justify-center">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-lg">
                <i data-lucide="clipboard-list" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Total Assignments</p>
                <h3 class="text-2xl font-bold">{{ $totalAssignments }}</h3>
            </div>
        </div>
    </div>

    {{-- Active Users --}}
    <div class="bg-card text-card-foreground rounded-xl border shadow-sm p-6 flex flex-col justify-center">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-amber-500/10 text-amber-500 rounded-lg">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-muted-foreground">Active Users Today</p>
                <h3 class="text-2xl font-bold">{{ $activeUsersToday }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Storage + file uploads --}}
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-card text-card-foreground rounded-xl border shadow-sm p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-red-500/10 text-red-500 rounded-lg">
                    <i data-lucide="hard-drive" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Storage Used</p>
                    <h3 class="text-2xl font-bold">{{ $storageUsedLabel }}</h3>
                </div>
            </div>
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $totalFileUploads }}</span> total files uploaded
            </div>
        </div>
    </div>

    {{-- Recent activity feed --}}
    <div class="lg:col-span-2">
        <div class="bg-card text-card-foreground rounded-xl border shadow-sm h-full flex flex-col">
            <div class="p-6 border-b">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-primary"></i>
                    Recent Activity
                </h3>
            </div>
            <div class="p-0 overflow-y-auto" style="max-height: 400px;">
                @forelse($recentActivity as $activity)
                    <div class="flex gap-4 p-4 {{ !$loop->last ? 'border-b border-border/50' : '' }} hover:bg-muted/50 transition-colors">
                        <div class="mt-1.5 flex-shrink-0">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary ring-4 ring-primary/20"></div>
                        </div>
                        <div>
                            <p class="text-sm">
                                @if($activity->user)
                                    <span class="font-semibold">{{ $activity->user->name }}</span>
                                @endif
                                <span class="text-muted-foreground">{{ $activity->description }}</span>
                            </p>
                            <span class="text-xs text-muted-foreground mt-1 block">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center p-8 text-center text-muted-foreground">
                        <i data-lucide="activity" class="w-8 h-8 mb-3 opacity-20"></i>
                        <p class="text-sm">No activity recorded yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Notifications — LetsBelajar')
@section('page-title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-semibold text-light">Notifications</h1>
    <button class="btn btn-outline-primary rounded-2" id="markAllRead">Mark all as read</button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notification)
            <a href="#" class="list-group-item list-group-item-action notification-item {{ is_null($notification->read_at) ? 'border-start border-4 border-primary' : '' }}" data-id="{{ $notification->id }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-light fw-medium">{{ $notification->data['title'] ?? $notification->type }}</p>
                        <small class="text-secondary">{{ $notification->data['message'] ?? '' }}</small>
                    </div>
                    <small class="text-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </a>
        @empty
            <div class="list-group-item text-center py-4 text-secondary">No notifications.</div>
        @endforelse
    </div>
</div>

<div class="mt-3">
    {{ $notifications->links() }}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (this.dataset.id) {
                fetch(`/notifications/${this.dataset.id}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
            }
        });
    });

    document.getElementById('markAllRead')?.addEventListener('click', function() {
        fetch('/notifications/mark-all-read', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        }).then(() => location.reload());
    });
});
</script>
@endpush
@endsection

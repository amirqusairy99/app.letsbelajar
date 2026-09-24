@extends('layouts.app')

@section('title', 'Notifications — LetsBelajar')
@section('page-title', 'Notifications')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-foreground">Notifications</h1>
        <p class="text-sm text-muted-foreground mt-1">Stay updated with your assignments and tasks.</p>
    </div>
    @if($notifications->count() > 0)
    <button id="markAllRead" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">
        <i data-lucide="check-check" class="w-4 h-4 me-2"></i> Mark all as read
    </button>
    @endif
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden mb-6">
    <div class="flex flex-col divide-y divide-border">
        @forelse($notifications as $notification)
        @php
            $isUnread = is_null($notification->read_at);
            // Get a clean title if none is provided in data
            $fallbackTitle = ucwords(str_replace('_', ' ', class_basename($notification->type)));
        @endphp
        <a href="#" class="group relative flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-5 hover:bg-muted/50 transition-colors notification-item {{ $isUnread ? 'bg-primary/5' : '' }}" data-id="{{ $notification->id }}">
            
            @if($isUnread)
                <!-- Unread Indicator (Left Border) -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-full unread-indicator"></div>
            @endif

            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full icon-container transition-colors {{ $isUnread ? 'bg-primary/20 text-primary' : 'bg-secondary text-muted-foreground' }}">
                    <i data-lucide="bell" class="h-5 w-5"></i>
                </div>
                
                <!-- Content -->
                <div class="flex flex-col">
                    <p class="text-sm font-semibold transition-colors title-text {{ $isUnread ? 'text-foreground' : 'text-muted-foreground' }}">
                        {{ $notification->data['title'] ?? $fallbackTitle }}
                    </p>
                    <p class="text-sm mt-1 transition-colors msg-text {{ $isUnread ? 'text-muted-foreground' : 'text-muted-foreground/70' }}">
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                    </p>
                </div>
            </div>

            <!-- Time -->
            <div class="mt-3 sm:mt-0 sm:ml-4 text-xs font-medium whitespace-nowrap transition-colors time-text {{ $isUnread ? 'text-primary' : 'text-muted-foreground/50' }}">
                {{ $notification->created_at->diffForHumans() }}
            </div>
        </a>
        @empty
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
            <div class="h-16 w-16 rounded-full bg-secondary flex items-center justify-center mb-4">
                <i data-lucide="bell-off" class="h-8 w-8 text-muted-foreground/50"></i>
            </div>
            <h3 class="text-lg font-semibold text-foreground">All caught up!</h3>
            <p class="text-sm text-muted-foreground mt-1">You have no new notifications.</p>
        </div>
        @endforelse
    </div>
</div>

@if($notifications->hasPages())
<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); 
            if (this.dataset.id) {
                // If unread, mark it as read visually and send API request
                if (this.classList.contains('bg-primary/5')) {
                    fetch(`/notifications/${this.dataset.id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    }).then(() => {
                        // Optimistic UI update: transition to read state
                        this.classList.remove('bg-primary/5');
                        
                        const unreadBorder = this.querySelector('.unread-indicator');
                        if (unreadBorder) unreadBorder.remove();
                        
                        const iconContainer = this.querySelector('.icon-container');
                        if (iconContainer) {
                            iconContainer.classList.remove('bg-primary/20', 'text-primary');
                            iconContainer.classList.add('bg-secondary', 'text-muted-foreground');
                        }
                        
                        const timeText = this.querySelector('.time-text');
                        if (timeText) {
                            timeText.classList.remove('text-primary');
                            timeText.classList.add('text-muted-foreground/50');
                        }
                        
                        const titleText = this.querySelector('.title-text');
                        if (titleText) {
                            titleText.classList.remove('text-foreground');
                            titleText.classList.add('text-muted-foreground');
                        }

                        const msgText = this.querySelector('.msg-text');
                        if (msgText) {
                            msgText.classList.remove('text-muted-foreground');
                            msgText.classList.add('text-muted-foreground/70');
                        }
                    });
                }
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

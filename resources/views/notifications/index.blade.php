@extends('layouts.app')

@section('title', 'Notifications — LetsBelajar')
@section('page-title', 'Notifications')

@section('content')
<div class="flex justify-between items-center mb-4">
 <h2 :text-5xl class="text-2xl font-semibold tracking-tight font-semibold text-foreground">Notifications</h2>
 <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-primary rounded-2" id="markAllRead">Mark all as read</button>
</div>

<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3">
 <div class="flex flex-col rounded-md border border-border bg-card flex flex-col rounded-md border border-border bg-card-flush">
 @forelse($notifications as $notification)
 <a href="#" class="relative flex w-full items-center justify-between border-b border-border py-3 px-4 last:border-0 hover:bg-muted/50 transition-colors relative flex w-full items-center justify-between border-b border-border py-3 px-4 last:border-0 hover:bg-muted/50 transition-colors-action notification-item {{ is_null($notification->read_at) ? 'border-start border-4 border-primary' : '' }}" data-id="{{ $notification->id }}">
 <div class="flex justify-between items-center">
 <div>
 <p class="mb-1 text-foreground font-medium">{{ $notification->data['title'] ?? $notification->type }}</p>
 <div class="text-muted-foreground">{{ $notification->data['message'] ?? '' }}</div>
 </div>
 <div class="text-muted-foreground">{{ $notification->created_at->diffForHumans() }}</div>
 </div>
 </a>
 @empty
 <div class="relative flex w-full items-center justify-between border-b border-border py-3 px-4 last:border-0 hover:bg-muted/50 transition-colors text-center py-4 text-muted-foreground">No notifications.</div>
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

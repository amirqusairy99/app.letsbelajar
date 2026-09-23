@extends('layouts.app')

@section('title', 'Kanban Board — LetsBelajar')
@section('page-title', 'Kanban Board')

@section('content')
<div class="flex justify-between items-center mb-4">
 <div>
 <h2 class="text-2xl font-semibold tracking-tight mb-1" style=" letter-spacing: -0.5px;">Kanban Board</h2>
 <p class="mb-0 text-sm" style="">{{ $assignment->name }} — drag tasks between columns</p>
 </div>
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
 <i data-lucide="arrow-left" class="w-4 h-4 me-1"></i> Back
 </a>
</div>

<div class="flex overflow-x-auto gap-6 pb-4 items-start min-h-[calc(100vh-200px)]">
 @foreach(['todo' => ['To Do', 'circle-dashed', 'var(--js-text-muted-foreground)'], 'doing' => ['In Progress', 'loader', 'var(--js-accent-hover)'], 'completed' => ['Completed', 'check-circle', 'var(--js-success)']] as $status => [$label, $icon, $color])
 @php($columnTasks = $tasks->where('status', $status))
 <div class="w-full md:w-[350px] flex-shrink-0">
 <div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm flex flex-col kanban-card-wrapper">
 <div class="flex flex-col space-y-1.5 p-6 border-b border-border border-0 py-3">
 <div class="flex justify-between items-center">
 <div class="flex items-center gap-2">
 <i data-lucide="{{ $icon }}" class="w-4 h-4" style="color: {{ $color }};"></i>
 <div class="font-semibold mb-0" style=" font-size: 0.875rem;">{{ $label }}</div>
 </div>
 <span class="badge bg-secondary rounded-pill">{{ $columnTasks->count() }}</span>
 </div>
 </div>
 <div class="kanban-column flex-1 min-h-[200px] p-3" data-status="{{ $status }}">
 @forelse($columnTasks as $task)
 <div class="kanban-card rounded-xl border border-border bg-card text-card-foreground shadow mb-2 p-3" draggable="true" data-task-id="{{ $task->id }}">
 <div class="py-2 px-1">
 <p class="mb-1 font-medium" style=" font-size: 0.875rem;">{{ $task->title }}</p>
 <div class="flex justify-between items-center">
 <div class="flex items-center gap-1">
 <span class="avatar" style="width:20px; height:20px; font-size: 0.5rem;">{{ strtoupper(substr($task->assignedTo->name ?? 'U', 0, 1)) }}</span>
 <div style=" font-size: 0.75rem;">{{ $task->assignedTo->name ?? 'Unassigned' }}</div>
 </div>
 <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary') }}" style="font-size: 0.65rem;">{{ ucfirst($task->priority) }}</span>
 </div>
 @if($task->due_date)
 <div class="mt-1 flex items-center gap-1">
 <i data-lucide="calendar" class="w-3 h-3" style=""></i>
 <div style=" font-size: 0.7rem;">{{ \Carbon\Carbon::parse($task->due_date)->format('M j') }}</div>
 </div>
 @endif
 </div>
 </div>
 @empty
 <div class="text-center py-4">
 <i data-lucide="inbox" class="w-5 h-5 mb-1" style=""></i>
 <p class="text-sm mb-0" style="">No tasks</p>
 </div>
 @endforelse
 </div>
 </div>
 </div>
 @endforeach
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
 const cards = document.querySelectorAll('.kanban-card');
 const columns = document.querySelectorAll('.kanban-column');
 let draggedCard = null;

 cards.forEach(card => {
 card.addEventListener('dragstart', function(e) {
 draggedCard = card;
 card.classList.add('dragging');
 });
 card.addEventListener('dragend', function(e) {
 card.classList.remove('dragging');
 draggedCard = null;
 columns.forEach(c => c.classList.remove('drag-over'));
 });
 });

 columns.forEach(column => {
 column.addEventListener('dragover', function(e) {
 e.preventDefault();
 column.classList.add('drag-over');
 });
 column.addEventListener('dragleave', function(e) {
 column.classList.remove('drag-over');
 });
 column.addEventListener('drop', function(e) {
 e.preventDefault();
 column.classList.remove('drag-over');
 if (!draggedCard) return;

 const taskId = draggedCard.dataset.taskId;
 const status = column.dataset.status;

 fetch(`/tasks/${taskId}/move`, {
 method: 'POST',
 headers: {
 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 },
 body: JSON.stringify({ status: status }),
 })
 .then(response => response.json())
 .then(data => {
 if (data.success) location.reload();
 });
 });
 });
});
</script>
@endpush
@endsection

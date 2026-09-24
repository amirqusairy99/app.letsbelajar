@extends('layouts.app')

@section('title', 'Kanban Board — LetsBelajar')
@section('page-title', 'Kanban Board')

@section('content')
<div class="flex justify-between items-center mb-4">
 <div>
 <h2 class="text-2xl font-semibold tracking-tight text-foreground mb-1">Kanban Board</h2>
 <p class="text-sm text-muted-foreground">{{ $assignment->name }} — drag tasks between columns</p>
 </div>
 <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
 <i data-lucide="arrow-left" class="w-4 h-4 me-1"></i> Back
 </a>
</div>

<div class="w-full overflow-x-auto pb-4">
 <div class="flex flex-row gap-6 min-w-[768px]">
 @foreach(['todo' => ['To Do', 'circle-dashed', 'var(--js-text-muted-foreground)'], 'doing' => ['In Progress', 'loader', 'var(--js-accent-hover)'], 'completed' => ['Completed', 'check-circle', 'var(--js-success)']] as $status => [$label, $icon, $color])
 @php($columnTasks = $tasks->where('status', $status))
 <div class="flex-1 w-1/3">
 <div class="rounded-xl border border-white/10 bg-secondary text-foreground shadow-sm flex flex-col kanban-card-wrapper h-full min-h-[300px]">
 <div class="flex flex-col space-y-1.5 p-6 border-b border-white/10 py-3">
 <div class="flex justify-between items-center">
 <div class="flex items-center gap-2">
 <i data-lucide="{{ $icon }}" class="w-4 h-4" style="color: {{ $color }};"></i>
 <div class="font-semibold text-sm">{{ $label }}</div>
 </div>
 <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-card border border-border text-xs font-medium">{{ $columnTasks->count() }}</span>
 </div>
 </div>
 <div class="kanban-column flex-1 min-h-[200px] p-3" data-status="{{ $status }}">
 @forelse($columnTasks as $task)
 <div class="kanban-card rounded-xl border border-border bg-card text-card-foreground shadow mb-2 p-3 hover:border-primary/50 transition-colors cursor-grab active:cursor-grabbing" draggable="true" data-task-id="{{ $task->id }}">
 <div class="flex flex-col h-full justify-between">
 <div>
 <p class="mb-2 font-medium text-sm leading-tight">{{ $task->title }}</p>
 </div>
 <div class="flex flex-col gap-2 mt-1">
 <div class="flex justify-between items-center">
 <div class="flex items-center gap-2 overflow-hidden">
 <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">
 {{ strtoupper(substr($task->assignedTo->name ?? 'U', 0, 1)) }}
 </span>
 <div class="text-xs text-muted-foreground truncate">{{ $task->assignedTo->name ?? 'Unassigned' }}</div>
 </div>
 <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium shrink-0 {{ $task->priority === 'high' ? 'bg-destructive/10 text-destructive' : ($task->priority === 'medium' ? 'bg-amber-500/10 text-amber-500 dark:text-amber-400' : 'bg-secondary text-secondary-foreground') }}">
 {{ ucfirst($task->priority) }}
 </span>
 </div>
 @if($task->due_date)
 <div class="flex items-center gap-1.5 text-xs text-muted-foreground mt-1">
 <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
 <span>{{ \Carbon\Carbon::parse($task->due_date)->format('M j') }}</span>
 </div>
 @endif
 </div>
 </div>
 </div>
 @empty
 <div class="text-center py-8">
 <i data-lucide="inbox" class="w-8 h-8 mb-2 text-muted-foreground/50 mx-auto"></i>
 <p class="text-sm text-muted-foreground">No tasks</p>
 </div>
 @endforelse
 </div>
 </div>
 </div>
 @endforeach
 </div>
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

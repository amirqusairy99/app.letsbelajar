@extends('layouts.app')

@section('title', 'Kanban Board — AyuhStudy')
@section('page-title', 'Kanban Board')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Kanban Board</h1>
        <p class="mb-0 small" style="color: var(--js-text-muted);">{{ $assignment->name }} — drag tasks between columns</p>
    </div>
    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-outline-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4 me-1"></i> Back
    </a>
</div>

<div class="row g-3">
    @foreach(['todo' => ['To Do', 'circle-dashed', 'var(--js-text-secondary)'], 'doing' => ['In Progress', 'loader', 'var(--js-accent-hover)'], 'completed' => ['Completed', 'check-circle-2', 'var(--js-success)']] as $status => [$label, $icon, $color])
        @php($columnTasks = $tasks->where('status', $status))
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm d-flex flex-column kanban-card-wrapper">
                <div class="card-header border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i data-lucide="{{ $icon }}" class="w-4 h-4" style="color: {{ $color }};"></i>
                            <h6 class="fw-semibold mb-0" style="color: var(--js-text-primary); font-size: 0.875rem;">{{ $label }}</h6>
                        </div>
                        <span class="badge bg-secondary rounded-pill">{{ $columnTasks->count() }}</span>
                    </div>
                </div>
                <div class="kanban-column" data-status="{{ $status }}">
                    @forelse($columnTasks as $task)
                        <div class="kanban-card mb-2" draggable="true" data-task-id="{{ $task->id }}">
                            <div class="card-body py-2 px-3">
                                <p class="mb-1 fw-medium" style="color: var(--js-text-primary); font-size: 0.875rem;">{{ $task->title }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="avatar" style="width:20px; height:20px; font-size: 0.5rem;">{{ strtoupper(substr($task->assignedTo->name ?? 'U', 0, 1)) }}</span>
                                        <small style="color: var(--js-text-muted); font-size: 0.75rem;">{{ $task->assignedTo->name ?? 'Unassigned' }}</small>
                                    </div>
                                    <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary') }}" style="font-size: 0.65rem;">{{ ucfirst($task->priority) }}</span>
                                </div>
                                @if($task->due_date)
                                <div class="mt-1 d-flex align-items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3" style="color: var(--js-text-muted);"></i>
                                    <small style="color: var(--js-text-muted); font-size: 0.7rem;">{{ \Carbon\Carbon::parse($task->due_date)->format('M j') }}</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i data-lucide="inbox" class="w-5 h-5 mb-1" style="color: var(--js-text-muted);"></i>
                            <p class="small mb-0" style="color: var(--js-text-muted);">No tasks</p>
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

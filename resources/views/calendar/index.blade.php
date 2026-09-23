@extends('layouts.app')

@section('title', 'Calendar — LetsBelajar')
@section('page-title', 'Calendar')

@section('content')
<div class="flex justify-between items-center mb-4">
 <div>
 <text-4xl font-extrabold tracking-tight lg:text-5xl class="text-2xl font-semibold tracking-tight font-bold mb-1" style="color: var(--js-text-primary); letter-spacing: -0.5px;">Calendar View</text-4xl font-extrabold tracking-tight lg:text-5xl>
 <p class="mb-0 text-sm" style="color: var(--js-text-muted-foreground);">Track your assignments and task deadlines visually.</p>
 </div>
 
 {{-- Legend --}}
 <div class="flex items-center gap-6 bg-dark border-0 p-2 px-3 rounded-3 shadow-sm" style="border: 1px solid rgba(255, 255, 255, 0.05) !important;">
 <span class="text-sm font-semibold text-secondary">Legend:</span>
 <div class="flex items-center gap-1">
 <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #3b82f6;"></span>
 <span class="text-sm text-secondary" style="font-size: 0.75rem;">Assignments</span>
 </div>
 <div class="flex items-center gap-1">
 <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #a855f7;"></span>
 <span class="text-sm text-secondary" style="font-size: 0.75rem;">Tasks</span>
 </div>
 </div>
</div>

{{-- Calendar Container --}}
<div class="rounded-xl border border-border bg-card text-card-foreground shadow border-0 shadow-sm rounded-3 mb-4 bg-dark" style="border: 1px solid rgba(255, 255, 255, 0.05) !important;">
 <div class="p-6 p-3 p-md-4">
 <div id="calendar" style="min-height: 650px;"></div>
 </div>
</div>

{{-- Event Details Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered">
 <div class="modal-content border-0 rounded-3 bg-dark" style="border: 1px solid rgba(255, 255, 255, 0.1) !important;">
 <div class="modal-header border-secondary py-2 px-3">
 <span class="badge" id="modalTypeBadge" style="font-size: 0.75rem;">Type</span>
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-close inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-close-white" data-bs-dismiss="modal"></button>
 </div>
 <div class="modal-body py-3 px-3 text-light">
 <text-xl font-semibold tracking-tight class="text-lg font-semibold tracking-tight font-bold mb-3" id="modalTitle">Event Title</text-xl font-semibold tracking-tight>
 
 <div class="space-y-3">
 <div id="modalSubjectRow" class="mb-2 d-none">
 <span class="text-secondary text-sm d-block">Subject</span>
 <span class="font-medium" id="modalSubject">Subject Value</span>
 </div>

 <div id="modalAssignmentRow" class="mb-2 d-none">
 <span class="text-secondary text-sm d-block">Related Assignment</span>
 <span class="font-medium" id="modalAssignment">Assignment Value</span>
 </div>

 <div id="modalLecturerRow" class="mb-2 d-none">
 <span class="text-secondary text-sm d-block">Lecturer</span>
 <span class="font-medium" id="modalLecturer">Lecturer Value</span>
 </div>

 <div class="row mb-2">
 <div class="col-6">
 <span class="text-secondary text-sm d-block">Due Date</span>
 <span class="font-medium" id="modalDueDate">Due Date Value</span>
 </div>
 <div class="col-6">
 <span class="text-secondary text-sm d-block">Status</span>
 <span class="badge" id="modalStatusBadge">Status Value</span>
 </div>
 </div>

 <div id="modalPriorityRow" class="mb-2 d-none">
 <span class="text-secondary text-sm d-block">Priority</span>
 <span class="badge" id="modalPriorityBadge">Priority Value</span>
 </div>

 <div class="mb-2">
 <span class="text-secondary text-sm d-block">Description</span>
 <p class="text-sm mb-0" id="modalDescription" style="white-space: pre-wrap; color: var(--js-text-secondary);"></p>
 </div>
 </div>
 </div>
 <div class="modal-footer border-secondary py-2 px-3">
 <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-outline-secondary rounded-2" data-bs-dismiss="modal">Close</button>
 <a href="#" id="modalActionBtn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-sm inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2-primary rounded-2">View Details</a>
 </div>
 </div>
 </div>
</div>

@push('scripts')
{{-- Load FullCalendar v6 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
 /* Dark Theme Customization for FullCalendar */
 .fc {
 --fc-border-color: rgba(255, 255, 255, 0.08);
 --fc-daygrid-event-dot-width: 8px;
 --fc-button-bg-color: rgba(255, 255, 255, 0.05);
 --fc-button-border-color: rgba(255, 255, 255, 0.1);
 --fc-button-hover-bg-color: rgba(255, 255, 255, 0.1);
 --fc-button-hover-border-color: rgba(255, 255, 255, 0.2);
 --fc-button-active-bg-color: var(--js-accent, #3b82f6);
 --fc-button-active-border-color: var(--js-accent, #3b82f6);
 --fc-event-bg-color: #3b82f6;
 --fc-event-border-color: #3b82f6;
 --fc-page-bg-color: #111827;
 color: #F1F5F9;
 font-family: inherit;
 }

 .fc .fc-toolbar-title {
 font-size: 1.25rem;
 font-weight: 700;
 letter-spacing: -0.3px;
 color: #F1F5F9;
 }

 .fc .fc-col-header-cell-cushion {
 color: #94A3B8;
 font-size: 0.85rem;
 font-weight: 600;
 text-transform: uppercase;
 padding: 8px 0;
 }

 .fc .fc-daygrid-day-number {
 color: #64748B;
 font-size: 0.875rem;
 padding: 6px 8px;
 text-decoration: none;
 }

 .fc .fc-day-today {
 background-color: rgba(59, 130, 246, 0.05) !important;
 }

 .fc .fc-day-today .fc-daygrid-day-number {
 color: var(--js-accent, #3b82f6);
 font-weight: 700;
 }

 /* Event Styles */
 .fc-event {
 cursor: pointer;
 padding: 3px 6px;
 border-radius: 4px;
 font-size: 0.75rem;
 font-weight: 500;
 border: none !important;
 }

 .fc-event-assignment {
 background-color: #3b82f6 !important; /* blue */
 color: #ffffff !important;
 }

 .fc-event-task {
 background-color: #a855f7 !important; /* purple */
 color: #ffffff !important;
 }

 .fc .fc-button-primary:disabled {
 background-color: rgba(255, 255, 255, 0.02);
 border-color: rgba(255, 255, 255, 0.05);
 color: #475569;
 }

 /* Mobile Adaptations */
 @media (max-width: 768px) {
 .fc .fc-toolbar {
 flex-direction: column;
 gap: 10px;
 }
 }
</style>

<script>
 document.addEventListener('DOMContentLoaded', function() {
 const calendarEl = document.getElementById('calendar');
 const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

 // Modal Elements
 const modalTypeBadge = document.getElementById('modalTypeBadge');
 const modalTitle = document.getElementById('modalTitle');
 const modalSubjectRow = document.getElementById('modalSubjectRow');
 const modalSubject = document.getElementById('modalSubject');
 const modalAssignmentRow = document.getElementById('modalAssignmentRow');
 const modalAssignment = document.getElementById('modalAssignment');
 const modalLecturerRow = document.getElementById('modalLecturerRow');
 const modalLecturer = document.getElementById('modalLecturer');
 const modalDueDate = document.getElementById('modalDueDate');
 const modalStatusBadge = document.getElementById('modalStatusBadge');
 const modalPriorityRow = document.getElementById('modalPriorityRow');
 const modalPriorityBadge = document.getElementById('modalPriorityBadge');
 const modalDescription = document.getElementById('modalDescription');
 const modalActionBtn = document.getElementById('modalActionBtn');

 const calendar = new FullCalendar.Calendar(calendarEl, {
 initialView: 'dayGridMonth',
 headerToolbar: {
 left: 'prev,next today',
 center: 'title',
 right: 'dayGridMonth,timeGridWeek,listMonth'
 },
 editable: false,
 selectable: false,
 events: "{{ route('calendar.events') }}",
 eventClick: function(info) {
 // Prevent redirection immediately
 info.jsEvent.preventDefault();

 const props = info.event.extendedProps;

 // Configure modal contents based on type
 if (props.type === 'Assignment') {
 modalTypeBadge.className = 'badge bg-primary uppercase';
 modalTypeBadge.textContent = 'Assignment';
 
 modalSubjectRow.classList.remove('d-none');
 modalSubject.textContent = props.subject;

 modalLecturerRow.classList.remove('d-none');
 modalLecturer.textContent = props.lecturer;

 modalAssignmentRow.classList.add('d-none');
 modalPriorityRow.classList.add('d-none');
 } else {
 modalTypeBadge.className = 'badge bg-purple uppercase';
 modalTypeBadge.textContent = 'Task';
 modalTypeBadge.style.backgroundColor = '#a855f7';

 modalAssignmentRow.classList.remove('d-none');
 modalAssignment.textContent = props.assignmentName;

 modalPriorityRow.classList.remove('d-none');
 modalPriorityBadge.textContent = props.priority;
 if (props.priority === 'High') {
 modalPriorityBadge.className = 'badge bg-danger';
 } else if (props.priority === 'Medium') {
 modalPriorityBadge.className = 'badge bg-warning text-dark';
 } else {
 modalPriorityBadge.className = 'badge bg-info';
 }

 modalSubjectRow.classList.add('d-none');
 modalLecturerRow.classList.add('d-none');
 }

 modalTitle.textContent = info.event.title.replace(/📚\s*|📝\s*/, '');
 modalDueDate.textContent = props.due;
 modalDescription.textContent = props.description;

 // Status Badge
 modalStatusBadge.textContent = props.status.toUpperCase();
 if (props.status === 'completed' || props.status === 'done') {
 modalStatusBadge.className = 'badge bg-success';
 } else if (props.status === 'in_progress' || props.status === 'doing') {
 modalStatusBadge.className = 'badge bg-warning text-dark';
 } else {
 modalStatusBadge.className = 'badge bg-secondary';
 }

 modalActionBtn.href = info.event.url;

 eventModal.show();
 }
 });

 calendar.render();
 });
</script>
@endpush
@endsection

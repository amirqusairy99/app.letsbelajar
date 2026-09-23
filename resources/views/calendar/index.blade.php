@extends('layouts.app')

@section('title', 'Calendar — LetsBelajar')
@section('page-title', 'Calendar')

@section('content')
<div x-data="calendarApp()" x-init="initCalendar()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Calendar View</h1>
            <p class="text-sm text-muted-foreground mt-1">Track your assignments and task deadlines visually.</p>
        </div>
        
        {{-- Legend --}}
        <div class="flex items-center gap-4 bg-card border border-border px-4 py-2 rounded-lg shadow-sm">
            <span class="text-sm font-semibold text-muted-foreground">Legend:</span>
            <div class="flex items-center gap-1.5">
                <span class="block w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span class="text-xs text-foreground">Assignments</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="block w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                <span class="text-xs text-foreground">Tasks</span>
            </div>
        </div>
    </div>

    {{-- Calendar Container --}}
    <div class="rounded-xl border border-border bg-card shadow-sm p-4 md:p-6 mb-4">
        <div id="calendar" class="min-h-[650px]"></div>
    </div>

    {{-- Tailwind Modal for Event Details (Managed by Alpine.js) --}}
    <div x-show="isModalOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="isModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-xl border border-border bg-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="border-b border-border px-4 py-3 flex justify-between items-center">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ring-1 ring-inset uppercase" 
                              :class="eventData.type === 'Assignment' ? 'bg-blue-500/10 text-blue-500 ring-blue-500/20' : 'bg-purple-500/10 text-purple-500 ring-purple-500/20'" 
                              x-text="eventData.type">Type</span>
                        <button type="button" @click="closeModal()" class="text-muted-foreground hover:text-foreground focus:outline-none">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-foreground mb-4" id="modal-title" x-text="eventData.title">Event Title</h3>
                        
                        <div class="space-y-4 text-sm">
                            <template x-if="eventData.type === 'Assignment'">
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Subject</span>
                                    <span class="text-foreground font-medium" x-text="eventData.subject"></span>
                                </div>
                            </template>
                            
                            <template x-if="eventData.type === 'Assignment' && eventData.lecturer">
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Lecturer</span>
                                    <span class="text-foreground font-medium" x-text="eventData.lecturer"></span>
                                </div>
                            </template>

                            <template x-if="eventData.type === 'Task'">
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Related Assignment</span>
                                    <span class="text-foreground font-medium" x-text="eventData.assignmentName"></span>
                                </div>
                            </template>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Due Date</span>
                                    <span class="text-foreground font-medium" x-text="eventData.due"></span>
                                </div>
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Status</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-secondary text-secondary-foreground uppercase" x-text="eventData.status"></span>
                                </div>
                            </div>

                            <template x-if="eventData.type === 'Task'">
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Priority</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ring-1 ring-inset" 
                                          :class="{'bg-red-500/10 text-red-500 ring-red-500/20': eventData.priority === 'High', 'bg-amber-500/10 text-amber-500 ring-amber-500/20': eventData.priority === 'Medium', 'bg-blue-500/10 text-blue-500 ring-blue-500/20': eventData.priority === 'Low'}"
                                          x-text="eventData.priority"></span>
                                </div>
                            </template>

                            <template x-if="eventData.description">
                                <div>
                                    <span class="block text-muted-foreground text-xs font-medium uppercase mb-1">Description</span>
                                    <p class="text-muted-foreground whitespace-pre-wrap" x-text="eventData.description"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-muted/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-border">
                        <a :href="eventData.url" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto">View Details</a>
                        <button type="button" @click="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-background px-3 py-2 text-sm font-semibold text-foreground shadow-sm ring-1 ring-inset ring-border hover:bg-accent sm:mt-0 sm:w-auto">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
 .fc {
    --fc-border-color: hsl(var(--border));
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: hsl(var(--muted));
    --fc-button-bg-color: hsl(var(--secondary));
    --fc-button-border-color: hsl(var(--border));
    --fc-button-hover-bg-color: hsl(var(--accent));
    --fc-button-hover-border-color: hsl(var(--border));
    --fc-button-active-bg-color: hsl(var(--primary));
    --fc-button-active-border-color: hsl(var(--primary));
    --fc-today-bg-color: hsla(var(--primary), 0.1);
    color: hsl(var(--foreground));
 }
 .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 700; color: hsl(var(--foreground)); }
 .fc .fc-col-header-cell-cushion { color: hsl(var(--muted-foreground)); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; padding: 8px 0; }
 .fc .fc-daygrid-day-number { color: hsl(var(--muted-foreground)); padding: 6px 8px; }
 .fc-event { cursor: pointer; padding: 2px 4px; border-radius: 4px; border: none; font-size: 0.75rem; font-weight: 500; }
 .fc-event-assignment { background-color: #3b82f6 !important; color: #fff !important; }
 .fc-event-task { background-color: #a855f7 !important; color: #fff !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('calendarApp', () => ({
        isModalOpen: false,
        eventData: {},
        
        closeModal() {
            this.isModalOpen = false;
        },
        
        initCalendar() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' },
                events: "{{ route('calendar.events') }}",
                eventClick: (info) => {
                    info.jsEvent.preventDefault();
                    const props = info.event.extendedProps;
                    
                    this.eventData = {
                        title: info.event.title.replace(/📚\s*|📝\s*/, ''),
                        type: props.type,
                        subject: props.subject,
                        lecturer: props.lecturer,
                        assignmentName: props.assignmentName,
                        due: props.due,
                        status: props.status,
                        priority: props.priority,
                        description: props.description,
                        url: info.event.url
                    };
                    
                    this.isModalOpen = true;
                }
            });
            calendar.render();
        }
    }));
});
</script>
@endpush
@endsection

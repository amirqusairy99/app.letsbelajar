<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $user = $request->user();
        
        $start = $request->query('start') ? Carbon::parse($request->query('start')) : now()->startOfMonth();
        $end = $request->query('end') ? Carbon::parse($request->query('end')) : now()->endOfMonth();

        // Fetch Assignments where the user is a member
        $assignments = Assignment::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereBetween('due_date', [$start, $end])
        ->get();

        $events = [];

        foreach ($assignments as $assignment) {
            $events[] = [
                'id' => 'assignment-' . $assignment->id,
                'title' => '📚 ' . ($assignment->subject ? '[' . $assignment->subject . '] ' : '') . $assignment->name,
                'start' => $assignment->due_date->toDateString(),
                'url' => route('assignments.show', $assignment),
                'className' => 'fc-event-assignment',
                'extendedProps' => [
                    'type' => 'Assignment',
                    'subject' => $assignment->subject ?? 'N/A',
                    'lecturer' => $assignment->lecturer_name ?? 'N/A',
                    'description' => $assignment->description ?? 'No description provided.',
                    'status' => $assignment->status ?? 'pending',
                    'due' => $assignment->due_date->format('M j, Y'),
                ],
            ];
        }

        // Fetch Tasks belonging to assignments where the user is a member
        $tasks = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereBetween('due_date', [$start, $end])
        ->with('assignment')
        ->get();

        foreach ($tasks as $task) {
            $events[] = [
                'id' => 'task-' . $task->id,
                'title' => '📝 ' . $task->title,
                'start' => $task->due_date->toDateString(),
                'url' => route('tasks.index', $task->assignment_id),
                'className' => 'fc-event-task',
                'extendedProps' => [
                    'type' => 'Task',
                    'assignmentName' => $task->assignment->name,
                    'description' => $task->description ?? 'No description provided.',
                    'status' => $task->status ?? 'pending',
                    'due' => $task->due_date->format('M j, Y'),
                    'priority' => ucfirst($task->priority ?? 'medium'),
                ],
            ];
        }

        return response()->json($events);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function events(Request $request)
    {
        $user = $request->user();
        $events = [];

        $assignments = Assignment::whereHas('members', fn ($q) => $q->where('user_id', $user->id))
            ->whereNotNull('due_date')
            ->get();

        foreach ($assignments as $assignment) {
            $events[] = [
                'id' => $assignment->id,
                'type' => 'assignment',
                'title' => ($assignment->subject ? '[' . $assignment->subject . '] ' : '') . $assignment->name,
                'start' => $assignment->due_date->toDateString(),
                'end' => null,
                'color' => '#4F46E5',
                'assignment_id' => $assignment->id,
            ];
        }

        $tasks = Task::whereHas('assignment.members', fn ($q) => $q->where('user_id', $user->id))
            ->whereNotNull('due_date')
            ->with('assignment')
            ->get();

        foreach ($tasks as $task) {
            $events[] = [
                'id' => $task->id,
                'type' => 'task',
                'title' => $task->title,
                'start' => $task->due_date->toDateString(),
                'end' => null,
                'color' => $task->status === 'completed' ? '#10B981' : '#F59E0B',
                'assignment_id' => $task->assignment_id,
                'task_id' => $task->id,
            ];
        }

        return response()->json($events);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Assignment;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    public function board(Request $request)
    {
        $user = $request->user();
        $firstMember = $user->assignmentMembers()->first();

        if (!$firstMember) {
            return view('kanban.empty');
        }

        return redirect()->route('kanban.index', $firstMember->assignment_id);
    }

    public function index(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load('members.user');
        
        $tasks = Task::where('assignment_id', $assignment->id)
            ->with('assignedTo')
            ->orderBy('priority', 'desc')
            ->get();

        return view('kanban.index', compact('assignment', 'tasks'));
    }

    public function move(Request $request, Task $task, NotificationService $notifications)
    {
        $request->validate([
            'status' => ['required', 'in:todo,doing,completed'],
        ]);

        $this->authorize('update', $task);
        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);

        if ($oldStatus !== $request->status && $request->status === 'completed') {
            Activity::create([
                'user_id' => auth()->id(),
                'assignment_id' => $task->assignment_id,
                'action' => 'task_completed',
                'description' => "Task '{$task->title}' was marked as completed.",
                'subject_type' => 'task',
                'subject_id' => $task->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $notifications->notifyAssignmentMembers(
                $task->assignment,
                'task_completed',
                [
                    'title' => 'Task completed',
                    'message' => "Task '{$task->title}' in {$task->assignment->name} was completed.",
                    'assignment_id' => $task->assignment_id,
                    'task_id' => $task->id,
                ],
                auth()->user()
            );
        }

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load('members.user');
        $tasks = Task::where('assignment_id', $assignment->id)
            ->with('assignedTo')
            ->latest()
            ->get();

        return view('tasks.index', compact('assignment', 'tasks'));
    }

    public function store(StoreTaskRequest $request, Assignment $assignment, NotificationService $notifications)
    {
        $this->authorize('view', $assignment);
        $member = $assignment->members()->where('user_id', auth()->id())->first();
        if (!$member || !in_array($member->role, ['owner'])) {
            abort(403, 'Only owners can assign tasks.');
        }

        $task = Task::create(array_merge($request->validated(), [
            'assignment_id' => $assignment->id,
            'created_by' => auth()->id(),
        ]));

        if ($task->assigned_to) {
            Activity::create([
                'user_id' => auth()->id(),
                'assignment_id' => $assignment->id,
                'action' => 'task_assigned',
                'description' => "Task '{$task->title}' assigned to {$task->assignedTo->name}.",
                'subject_type' => 'task',
                'subject_id' => $task->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $notifications->notify(
                $task->assignedTo,
                'task_assigned',
                [
                    'title' => 'New task assigned',
                    'message' => "{$task->assignedTo->name}, you were assigned to '{$task->title}' in {$assignment->name}.",
                    'assignment_id' => $assignment->id,
                    'task_id' => $task->id,
                ]
            );
        }

        return redirect()->route('tasks.index', $assignment)->with('success', 'Task created successfully.');
    }

    public function edit(Assignment $assignment, Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('assignment', 'task'));
    }

    public function update(UpdateTaskRequest $request, Assignment $assignment, Task $task, NotificationService $notifications)
    {
        $this->authorize('update', $task);
        $wasCompleted = $task->status === 'completed';
        $task->update($request->validated());

        if ($task->status === 'completed' && !$wasCompleted) {
            Activity::create([
                'user_id' => auth()->id(),
                'assignment_id' => $assignment->id,
                'action' => 'task_completed',
                'description' => "Task '{$task->title}' was marked as completed" . ($task->assignedTo ? " by {$task->assignedTo->name}" : '') . ".",
                'subject_type' => 'task',
                'subject_id' => $task->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $notifications->notifyAssignmentMembers(
                $assignment,
                'task_completed',
                [
                    'title' => 'Task completed',
                    'message' => "Task '{$task->title}' in {$assignment->name} was completed.",
                    'assignment_id' => $assignment->id,
                    'task_id' => $task->id,
                ],
                auth()->user()
            );
        }

        return redirect()->route('tasks.index', $assignment)->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, Assignment $assignment, Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'task_deleted',
            'description' => "Task '{$task->title}' was deleted.",
            'subject_type' => 'task',
            'subject_id' => $task->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('tasks.index', $assignment)->with('success', 'Task deleted successfully.');
    }
}

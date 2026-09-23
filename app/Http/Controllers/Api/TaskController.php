<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $tasks = Task::where('assignment_id', $assignment->id)
            ->with('assignedTo')
            ->latest()
            ->get()
            ->map(fn ($t) => $this->payload($t));

        return response()->json(['data' => $tasks]);
    }

    public function store(Request $request, Assignment $assignment, NotificationService $notifications)
    {
        $this->authorize('view', $assignment);
        $member = $assignment->members()->where('user_id', Auth::id())->first();
        if (! $member || $member->role !== 'owner') {
            return response()->json(['message' => 'Only owners can assign tasks.'], 403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'status' => ['nullable', 'in:to_do,in_progress,completed'],
        ]);

        $task = Task::create(array_merge($request->only([
            'title', 'description', 'assigned_to', 'due_date', 'priority', 'status',
        ]), [
            'assignment_id' => $assignment->id,
            'created_by' => Auth::id(),
        ]));

        if ($task->assigned_to) {
            Activity::create([
                'user_id' => Auth::id(),
                'assignment_id' => $assignment->id,
                'action' => 'task_assigned',
                'description' => "Task '{$task->title}' assigned to {$task->assignedTo->name}.",
                'subject_type' => 'task',
                'subject_id' => $task->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $notifications->notify($task->assignedTo, 'task_assigned', [
                'title' => 'New task assigned',
                'message' => "{$task->assignedTo->name}, you were assigned to '{$task->title}' in {$assignment->name}.",
                'assignment_id' => $assignment->id,
                'task_id' => $task->id,
            ]);
        }

        return response()->json(['data' => $this->payload($task->load('assignedTo'))], 201);
    }

    public function update(Request $request, Assignment $assignment, Task $task, NotificationService $notifications)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'status' => ['nullable', 'in:to_do,in_progress,completed'],
        ]);

        $wasCompleted = $task->status === 'completed';
        $task->update($request->only([
            'title', 'description', 'assigned_to', 'due_date', 'priority', 'status',
        ]));

        if ($task->status === 'completed' && ! $wasCompleted) {
            Activity::create([
                'user_id' => Auth::id(),
                'assignment_id' => $assignment->id,
                'action' => 'task_completed',
                'description' => "Task '{$task->title}' was marked as completed.",
                'subject_type' => 'task',
                'subject_id' => $task->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $notifications->notifyAssignmentMembers($assignment, 'task_completed', [
                'title' => 'Task completed',
                'message' => "Task '{$task->title}' in {$assignment->name} was completed.",
                'assignment_id' => $assignment->id,
                'task_id' => $task->id,
            ], Auth::user());
        }

        return response()->json(['data' => $this->payload($task->load('assignedTo'))]);
    }

    public function destroy(Request $request, Assignment $assignment, Task $task)
    {
        $this->authorize('delete', $task);

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'task_deleted',
            'description' => "Task '{$task->title}' was deleted.",
            'subject_type' => 'task',
            'subject_id' => $task->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully.']);
    }

    public function move(Request $request, Task $task, NotificationService $notifications)
    {
        $request->validate(['status' => ['required', 'in:to_do,in_progress,completed']]);
        $this->authorize('update', $task);

        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);

        if ($oldStatus !== $request->status && $request->status === 'completed') {
            Activity::create([
                'user_id' => Auth::id(),
                'assignment_id' => $task->assignment_id,
                'action' => 'task_completed',
                'description' => "Task '{$task->title}' was marked as completed.",
                'subject_type' => 'task',
                'subject_id' => $task->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $notifications->notifyAssignmentMembers($task->assignment, 'task_completed', [
                'title' => 'Task completed',
                'message' => "Task '{$task->title}' in {$task->assignment->name} was completed.",
                'assignment_id' => $task->assignment_id,
                'task_id' => $task->id,
            ], Auth::user());
        }

        return response()->json(['data' => $this->payload($task->load('assignedTo'))]);
    }

    protected function payload(Task $task): array
    {
        return [
            'id' => $task->id,
            'assignment_id' => $task->assignment_id,
            'title' => $task->title,
            'description' => $task->description,
            'assigned_to' => $task->assigned_to,
            'due_date' => $task->due_date?->toDateString(),
            'priority' => $task->priority,
            'status' => $task->status,
            'created_by' => $task->created_by,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'assigned_user' => $task->assignedTo ? [
                'id' => $task->assignedTo->id,
                'name' => $task->assignedTo->name,
                'email' => $task->assignedTo->email,
            ] : null,
        ];
    }
}

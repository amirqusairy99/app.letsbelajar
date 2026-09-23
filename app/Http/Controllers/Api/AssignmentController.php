<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\Folder;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = Assignment::where('created_by', Auth::id())
            ->orWhereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
            ->with('createdBy')
            ->withCount('tasks')
            ->latest()
            ->get()
            ->map(fn ($a) => $this->payload($a));

        return response()->json(['data' => $assignments]);
    }

    public function show(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load('createdBy', 'members.user', 'tasks');
        return response()->json(['data' => $this->payload($assignment, true)]);
    }

    public function store(Request $request, NotificationService $notifications)
    {
        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'lecturer_name' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,completed,archived'],
        ]);

        $assignment = Assignment::create(array_merge($request->only([
            'subject', 'name', 'description', 'lecturer_name', 'due_date', 'status',
        ]), [
            'created_by' => Auth::id(),
        ]));

        AssignmentMember::create([
            'assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        foreach (['Report', 'Source Code', 'Slides', 'Research', 'Images', 'References'] as $folderName) {
            Folder::create([
                'assignment_id' => $assignment->id,
                'name' => $folderName,
                'path' => "assignments/{$assignment->id}",
                'created_by' => Auth::id(),
            ]);
        }

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_created',
            'description' => "Assignment '{$assignment->name}' was created.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['data' => $this->payload($assignment)], 201);
    }

    public function update(Request $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $request->validate([
            'subject' => ['sometimes', 'string', 'max:255'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'lecturer_name' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,completed,archived'],
        ]);

        $assignment->update($request->only([
            'subject', 'name', 'description', 'lecturer_name', 'due_date', 'status',
        ]));

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_updated',
            'description' => "Assignment '{$assignment->name}' was updated.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['data' => $this->payload($assignment)]);
    }

    public function destroy(Request $request, Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_deleted',
            'description' => "Assignment '{$assignment->name}' was deleted.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted successfully.']);
    }

    public function archive(Request $request, Assignment $assignment)
    {
        $this->authorize('archive', $assignment);
        $assignment->update(['status' => 'archived']);

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_archived',
            'description' => "Assignment '{$assignment->name}' was archived.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['data' => $this->payload($assignment)]);
    }

    public function unarchive(Request $request, Assignment $assignment)
    {
        $this->authorize('archive', $assignment);
        $assignment->update(['status' => 'active']);

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_unarchived',
            'description' => "Assignment '{$assignment->name}' was unarchived.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['data' => $this->payload($assignment)]);
    }

    public function analytics(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);

        $totalTasks = $assignment->tasks()->count();
        $completedTasks = $assignment->tasks()->where('status', 'completed')->count();
        $inProgress = $assignment->tasks()->where('status', 'in_progress')->count();
        $pending = $assignment->tasks()->where('status', 'to_do')->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $members = $assignment->members()->with('user')->get()->map(function ($member) use ($assignment) {
            return [
                'user' => $member->user,
                'role' => $member->role,
                'tasks_assigned' => $assignment->tasks()->where('assigned_to', $member->user_id)->count(),
                'tasks_completed' => $assignment->tasks()
                    ->where('assigned_to', $member->user_id)
                    ->where('status', 'completed')
                    ->count(),
                'files_uploaded' => $assignment->files()->where('uploaded_by', $member->user_id)->count(),
            ];
        });

        return response()->json([
            'assignment_id' => $assignment->id,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'in_progress_tasks' => $inProgress,
            'pending_tasks' => $pending,
            'progress_percentage' => $progress,
            'member_contributions' => $members,
        ]);
    }

    protected function payload(Assignment $assignment, bool $detailed = false): array
    {
        return [
            'id' => $assignment->id,
            'subject' => $assignment->subject,
            'name' => $assignment->name,
            'description' => $assignment->description,
            'lecturer_name' => $assignment->lecturer_name,
            'due_date' => $assignment->due_date?->toDateString(),
            'status' => $assignment->status,
            'created_by' => $assignment->created_by,
            'created_at' => $assignment->created_at,
            'updated_at' => $assignment->updated_at,
            'created_by_user' => $assignment->createdBy ? [
                'id' => $assignment->createdBy->id,
                'name' => $assignment->createdBy->name,
                'email' => $assignment->createdBy->email,
            ] : null,
            'members_count' => $assignment->members_count ?? $assignment->members()->count(),
            'tasks_count' => $assignment->tasks_count ?? $assignment->tasks()->count(),
        ];
    }
}

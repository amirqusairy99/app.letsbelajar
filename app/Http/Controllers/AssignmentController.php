<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\Folder;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::where('created_by', auth()->id())
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('members.user')
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('assignments.create');
    }

    public function store(StoreAssignmentRequest $request, NotificationService $notifications)
    {
        $assignment = Assignment::create(array_merge($request->validated(), [
            'created_by' => auth()->id(),
        ]));

        AssignmentMember::create([
            'assignment_id' => $assignment->id,
            'user_id' => auth()->id(),
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        foreach (['Report', 'Source Code', 'Slides', 'Research', 'Images', 'References'] as $folderName) {
            Folder::create([
                'assignment_id' => $assignment->id,
                'name' => $folderName,
                'path' => "assignments/{$assignment->id}",
                'created_by' => auth()->id(),
            ]);
        }

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_created',
            'description' => "Assignment '{$assignment->name}' was created.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load('members.user', 'tasks');
        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        $this->authorize('update', $assignment);
        return view('assignments.edit', compact('assignment'));
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment);
        $assignment->update($request->validated());

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_updated',
            'description' => "Assignment '{$assignment->name}' was updated.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Request $request, Assignment $assignment)
    {
        $this->authorize('delete', $assignment);
        
        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_deleted',
            'description' => "Assignment '{$assignment->name}' was deleted.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully.');
    }

    public function archive(Request $request, Assignment $assignment)
    {
        $this->authorize('archive', $assignment);
        $assignment->update(['status' => 'archived']);

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_archived',
            'description' => "Assignment '{$assignment->name}' was archived.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Assignment archived successfully.');
    }

    public function unarchive(Request $request, Assignment $assignment)
    {
        $this->authorize('archive', $assignment);
        $assignment->update(['status' => 'active']);

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'assignment_unarchived',
            'description' => "Assignment '{$assignment->name}' was unarchived.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Assignment unarchived successfully.');
    }

    public function analytics(Assignment $assignment)
    {
        $this->authorize('view', $assignment);

        $assignment->load('members.user');

        // Pre-fetch counts for all users in this assignment to eliminate N+1 queries
        $filesUploadedCounts = \App\Models\File::where('assignment_id', $assignment->id)
            ->select('uploaded_by', \DB::raw('count(*) as total'))
            ->groupBy('uploaded_by')
            ->pluck('total', 'uploaded_by');

        $tasksAssignedCounts = \App\Models\Task::where('assignment_id', $assignment->id)
            ->select('assigned_to', \DB::raw('count(*) as total'))
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to');

        $tasksCompletedCounts = \App\Models\Task::where('assignment_id', $assignment->id)
            ->where('status', 'completed')
            ->select('assigned_to', \DB::raw('count(*) as total'))
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to');

        $tasksCreatedCounts = \App\Models\Task::where('assignment_id', $assignment->id)
            ->select('created_by', \DB::raw('count(*) as total'))
            ->groupBy('created_by')
            ->pluck('total', 'created_by');

        $activitiesCounts = Activity::where('assignment_id', $assignment->id)
            ->select('user_id', \DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        $recentActivitiesGrouped = Activity::where('assignment_id', $assignment->id)
            ->latest()
            ->get()
            ->groupBy('user_id');

        $memberStats = [];

        foreach ($assignment->members as $member) {
            $user = $member->user;

            $filesUploadedCount = $filesUploadedCounts->get($user->id, 0);
            $tasksAssignedCount = $tasksAssignedCounts->get($user->id, 0);
            $tasksCompletedCount = $tasksCompletedCounts->get($user->id, 0);
            $tasksCreatedCount = $tasksCreatedCounts->get($user->id, 0);
            $activitiesCount = $activitiesCounts->get($user->id, 0);
            $recentActivities = $recentActivitiesGrouped->get($user->id, collect())->take(5);

            $memberStats[] = [
                'user' => $user,
                'role' => $member->role,
                'files_uploaded' => $filesUploadedCount,
                'tasks_assigned' => $tasksAssignedCount,
                'tasks_completed' => $tasksCompletedCount,
                'tasks_created' => $tasksCreatedCount,
                'activities_count' => $activitiesCount,
                'recent_activities' => $recentActivities,
            ];
        }

        // Sort by activities count descending
        usort($memberStats, function ($a, $b) {
            return $b['activities_count'] <=> $a['activities_count'];
        });

        $totalTasks = \App\Models\Task::where('assignment_id', $assignment->id)->count();
        $completedTasks = \App\Models\Task::where('assignment_id', $assignment->id)->where('status', 'completed')->count();
        $totalFiles = \App\Models\File::where('assignment_id', $assignment->id)->count();
        $totalActivities = $totalActivities = $activitiesCounts->sum();

        return view('assignments.analytics', compact('assignment', 'memberStats', 'totalTasks', 'completedTasks', 'totalFiles', 'totalActivities'));
    }
}

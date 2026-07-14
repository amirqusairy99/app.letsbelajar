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
}

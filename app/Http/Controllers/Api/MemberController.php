<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        $members = $assignment->members()->with('user')->get()->map(fn ($m) => [
            'id' => $m->id,
            'assignment_id' => $m->assignment_id,
            'user_id' => $m->user_id,
            'role' => $m->role,
            'joined_at' => $m->joined_at,
            'user' => [
                'id' => $m->user->id,
                'name' => $m->user->name,
                'email' => $m->user->email,
            ],
        ]);

        return response()->json(['data' => $members]);
    }

    public function store(Request $request, Assignment $assignment, NotificationService $notifications)
    {
        $this->authorize('manageMembers', $assignment);

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:owner,member'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($assignment->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User is already a member of this assignment.'], 422);
        }

        AssignmentMember::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'role' => $request->role,
            'joined_at' => now(),
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'assignment_id' => $assignment->id,
            'action' => 'member_added',
            'description' => "{$user->name} was added as {$request->role}.",
            'subject_type' => 'member',
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $notifications->notify($user, 'member_added', [
            'title' => 'Added to an assignment',
            'message' => "You were added to '{$assignment->name}' as {$request->role}.",
            'assignment_id' => $assignment->id,
        ]);

        return response()->json(['message' => 'Member added successfully.'], 201);
    }

    public function update(Request $request, Assignment $assignment, AssignmentMember $member)
    {
        $this->authorize('manageMembers', $assignment);
        $request->validate(['role' => ['required', 'in:owner,member']]);
        $member->update(['role' => $request->role]);
        return response()->json(['message' => 'Member role updated successfully.']);
    }

    public function destroy(Request $request, Assignment $assignment, AssignmentMember $member)
    {
        $this->authorize('manageMembers', $assignment);

        if ($member->user_id === $assignment->created_by) {
            return response()->json(['message' => 'Cannot remove the assignment owner.'], 422);
        }

        $member->delete();
        return response()->json(['message' => 'Member removed successfully.']);
    }
}

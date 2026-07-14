<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function store(Request $request, Assignment $assignment, NotificationService $notifications)
    {
        $this->authorize('manageMembers', $assignment);

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:owner,member'],
        ]);

        $user = User::where('email', $request->email)->first();

        $existing = AssignmentMember::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'User is already a member of this assignment.');
        }

        AssignmentMember::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'role' => $request->role,
            'joined_at' => now(),
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'member_added',
            'description' => "{$user->name} was added as {$request->role}.",
            'subject_type' => 'member',
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $notifications->notify(
            $user,
            'member_added',
            [
                'title' => 'Added to an assignment',
                'message' => "You were added to '{$assignment->name}' as {$request->role}.",
                'assignment_id' => $assignment->id,
            ]
        );

        return back()->with('success', 'Member added successfully.');
    }

    public function destroy(Request $request, Assignment $assignment, AssignmentMember $member)
    {
        $this->authorize('manageMembers', $assignment);

        if ($member->user_id === $assignment->created_by) {
            return back()->with('error', 'Cannot remove the assignment owner.');
        }

        $user = $member->user;

        Activity::create([
            'user_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'action' => 'member_removed',
            'description' => "{$user->name} was removed from the assignment.",
            'subject_type' => 'member',
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $member->delete();

        return back()->with('success', 'Member removed successfully.');
    }

    public function update(Request $request, Assignment $assignment, AssignmentMember $member)
    {
        $this->authorize('manageMembers', $assignment);

        $request->validate([
            'role' => ['required', 'in:owner,member'],
        ]);

        $member->update(['role' => $request->role]);

        return back()->with('success', 'Member role updated successfully.');
    }
}

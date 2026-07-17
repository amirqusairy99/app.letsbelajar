<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssignmentPolicy
{
    public function view(User $user, Assignment $assignment)
    {
        return $assignment->members()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You do not have access to this assignment.');
    }

    public function upload(User $user, Assignment $assignment)
    {
        return $assignment->members()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You do not have access to this assignment.');
    }

    public function update(User $user, Assignment $assignment)
    {
        $member = $assignment->members()->where('user_id', $user->id)->first();
        return $member && $member->role === 'owner'
            ? Response::allow()
            : Response::deny('Only owners can edit assignments.');
    }

    public function delete(User $user, Assignment $assignment)
    {
        $member = $assignment->members()->where('user_id', $user->id)->first();
        return $member && $member->role === 'owner'
            ? Response::allow()
            : Response::deny('Only owners can delete assignments.');
    }

    public function archive(User $user, Assignment $assignment)
    {
        $member = $assignment->members()->where('user_id', $user->id)->first();
        return $member && $member->role === 'owner'
            ? Response::allow()
            : Response::deny('Only owners can archive assignments.');
    }

    public function manageMembers(User $user, Assignment $assignment)
    {
        $member = $assignment->members()->where('user_id', $user->id)->first();
        return $member && $member->role === 'owner'
            ? Response::allow()
            : Response::deny('Only owners can manage members.');
    }
}

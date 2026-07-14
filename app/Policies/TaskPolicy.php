<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function view(User $user, Task $task)
    {
        return $task->assignment->members()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You do not have access to this task.');
    }

    public function update(User $user, Task $task)
    {
        $member = $task->assignment->members()->where('user_id', $user->id)->first();
        if (!$member) {
            return Response::deny('You do not have access to this task.');
        }
        return $member->role === 'owner' || $task->assigned_to === $user->id
            ? Response::allow()
            : Response::deny('Only owners or assigned users can update tasks.');
    }

    public function delete(User $user, Task $task)
    {
        $member = $task->assignment->members()->where('user_id', $user->id)->first();
        return $member && $member->role === 'owner'
            ? Response::allow()
            : Response::deny('Only owners can delete tasks.');
    }
}

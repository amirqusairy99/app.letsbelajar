<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function upload(User $user, Assignment $assignment)
    {
        return $assignment->members()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You do not have access to upload files.');
    }

    public function view(User $user, $file)
    {
        return $file->assignment->members()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('You do not have access to this file.');
    }

    public function delete(User $user, $file)
    {
        $member = $file->assignment->members()->where('user_id', $user->id)->first();
        return $member && $member->role === 'owner'
            ? Response::allow()
            : Response::deny('Only owners can delete files.');
    }
}

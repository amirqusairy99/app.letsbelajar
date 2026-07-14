<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
{
    public function view(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id
            ? Response::allow()
            : Response::deny('You do not have access to this notification.');
    }

    public function update(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id
            ? Response::allow()
            : Response::deny('You cannot update this notification.');
    }
}

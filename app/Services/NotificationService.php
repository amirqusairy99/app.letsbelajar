<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function notify(User $user, string $type, array $data): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function notifyAssignmentMembers($assignment, string $type, array $data, ?User $except = null): void
    {
        foreach ($assignment->members as $member) {
            if ($except && $member->user_id === $except->id) {
                continue;
            }

            $this->notify($member->user, $type, $data);
        }
    }
}

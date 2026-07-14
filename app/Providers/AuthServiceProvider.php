<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\File;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Assignment::class => \App\Policies\AssignmentPolicy::class,
        Task::class => \App\Policies\TaskPolicy::class,
        File::class => \App\Policies\FilePolicy::class,
        Notification::class => \App\Policies\NotificationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url(route('password.reset', $token, false));
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return url(route('verification.verify', [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ], false));
        });
    }
}

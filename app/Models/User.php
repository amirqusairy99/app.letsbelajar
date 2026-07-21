<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firebase_uid',
        'is_admin',
        'is_disabled',
        'disabled_at',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_disabled' => 'boolean',
        'disabled_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isDisabled(): bool
    {
        return (bool) $this->is_disabled;
    }

    public function createdAssignments()
    {
        return $this->hasMany(Assignment::class, 'created_by');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function assignmentMembers()
    {
        return $this->hasMany(AssignmentMember::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    public function readNotifications()
    {
        return $this->hasMany(Notification::class)->whereNotNull('read_at');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@jomstudy.app',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $assignment = Assignment::create([
            'subject' => 'Software Engineering',
            'name' => 'Final Year Project',
            'description' => 'Build a complete web application for study management.',
            'lecturer_name' => 'Dr. Smith',
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        AssignmentMember::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        foreach (['Report', 'Source Code', 'Slides', 'Research', 'Images', 'References'] as $folderName) {
            \App\Models\Folder::create([
                'assignment_id' => $assignment->id,
                'name' => $folderName,
                'path' => "assignments/{$assignment->id}",
                'created_by' => $user->id,
            ]);
        }

        $tasks = [
            ['title' => 'Design database schema', 'priority' => 'high', 'status' => 'completed'],
            ['title' => 'Implement authentication', 'priority' => 'high', 'status' => 'completed'],
            ['title' => 'Build dashboard', 'priority' => 'medium', 'status' => 'doing'],
            ['title' => 'Create Kanban board', 'priority' => 'medium', 'status' => 'todo'],
            ['title' => 'Add file upload', 'priority' => 'low', 'status' => 'todo'],
        ];

        foreach ($tasks as $taskData) {
            Task::create(array_merge([
                'assignment_id' => $assignment->id,
                'assigned_to' => $user->id,
                'created_by' => $user->id,
                'due_date' => now()->addDays(rand(1, 20))->format('Y-m-d'),
            ], $taskData));
        }
    }
}

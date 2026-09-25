<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\Task;
use App\Models\Folder;
use App\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SeedMamir extends Command
{
    protected $signature = 'app:seed-mamir';
    protected $description = 'Seed realistic data for Mamir';

    public function handle()
    {
        $email = 'mamirqusairy.arzri@student.gmi.edu.my';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->info("User not found! Creating user...");
            $user = User::create([
                'name' => 'Mamir Qusairy',
                'email' => $email,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);
        }

        // Delete old assignments to avoid duplicates
        $oldAssignments = Assignment::where('created_by', $user->id)->get();
        foreach ($oldAssignments as $old) {
            Activity::where('assignment_id', $old->id)->delete();
            Task::where('assignment_id', $old->id)->delete();
            Folder::where('assignment_id', $old->id)->delete();
            AssignmentMember::where('assignment_id', $old->id)->delete();
            $old->delete();
        }
        $this->info("Cleared old data.");

        $projects = [
            ['subject' => 'Data Structures', 'name' => 'Red-Black Tree Implementation', 'desc' => 'Implement and analyze a red-black tree with insertion, deletion, and search functionalities.', 'lecturer' => 'Dr. Ahmad Zaki'],
            ['subject' => 'Operating Systems', 'name' => 'Custom Shell Development', 'desc' => 'Build a Unix-like shell in C supporting pipes, redirection, and background processes.', 'lecturer' => 'Prof. Sarah Lee'],
            ['subject' => 'Web Development', 'name' => 'E-Commerce Platform MVP', 'desc' => 'Develop a full-stack e-commerce web app using Laravel, Tailwind, and MySQL.', 'lecturer' => 'Ts. Khairul Anwar'],
            ['subject' => 'Artificial Intelligence', 'name' => 'Neural Network Digit Recognizer', 'desc' => 'Train a simple feedforward neural network to recognize handwritten digits from the MNIST dataset.', 'lecturer' => 'Dr. Liew Chee Sun'],
            ['subject' => 'Computer Networks', 'name' => 'Network Topology Simulation', 'desc' => 'Design and simulate an enterprise network topology using Cisco Packet Tracer.', 'lecturer' => 'En. Hafizuddin']
        ];

        foreach ($projects as $proj) {
            $assignment = Assignment::create([
                'subject' => $proj['subject'],
                'name' => $proj['name'],
                'description' => $proj['desc'],
                'lecturer_name' => $proj['lecturer'],
                'due_date' => now()->addDays(rand(5, 30))->format('Y-m-d'),
                'status' => 'active',
                'created_by' => $user->id,
            ]);

            AssignmentMember::create([
                'assignment_id' => $assignment->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            $folders = ['Lectures', 'Tutorials', 'Lab', 'Submissions', 'Resources'];
            foreach ($folders as $folderName) {
                Folder::create([
                    'assignment_id' => $assignment->id,
                    'name' => $folderName,
                    'path' => "assignments/{$assignment->id}",
                    'created_by' => $user->id,
                ]);
            }

            $taskTitles = ['Draft Project Proposal', 'Gather Requirements', 'Write Source Code', 'Create Presentation Slides', 'Final Review and Submission'];
            foreach ($taskTitles as $title) {
                $status = ['todo', 'doing', 'completed'][rand(0, 2)];
                $priority = ['low', 'medium', 'high'][rand(0, 2)];
                
                Task::create([
                    'assignment_id' => $assignment->id,
                    'title' => $title,
                    'priority' => $priority,
                    'status' => $status,
                    'assigned_to' => $user->id,
                    'created_by' => $user->id,
                    'due_date' => now()->addDays(rand(-5, 15))->format('Y-m-d'),
                ]);
            }
            
            Activity::create([
                'user_id' => $user->id,
                'assignment_id' => $assignment->id,
                'action' => 'created_assignment',
                'description' => "created assignment {$assignment->name}",
                'created_at' => now()->subDays(rand(1, 5)),
            ]);
            
            // Add some more realistic activities
            Activity::create([
                'user_id' => $user->id,
                'assignment_id' => $assignment->id,
                'action' => 'updated_task',
                'description' => "updated task status for {$assignment->name}",
                'created_at' => now()->subHours(rand(1, 24)),
            ]);
        }

        $this->info("Seeded realistic data for {$email} successfully!");
    }
}

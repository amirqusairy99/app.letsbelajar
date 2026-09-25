<?php
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentMember;
use App\Models\Task;
use App\Models\Folder;
use App\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

$email = 'mamirqusairy.arzri@student.gmi.edu.my';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found! Creating user...\n";
    $user = User::create([
        'name' => 'Mamir Qusairy',
        'email' => $email,
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);
}

$subjects = ['Data Structures', 'Operating Systems', 'Web Development', 'Artificial Intelligence', 'Computer Networks'];

foreach ($subjects as $index => $subject) {
    $assignment = Assignment::create([
        'subject' => $subject,
        'name' => 'Assignment ' . ($index + 1),
        'description' => "Complete the {$subject} assignment.",
        'lecturer_name' => 'Dr. Lecturer ' . $index,
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

    $folders = ['Lectures', 'Tutorials', 'Lab', 'Submissions'];
    foreach ($folders as $folderName) {
        Folder::create([
            'assignment_id' => $assignment->id,
            'name' => $folderName,
            'path' => "assignments/{$assignment->id}",
            'created_by' => $user->id,
        ]);
    }

    $taskTitles = ['Read Chapter ' . rand(1, 5), 'Complete Lab ' . rand(1, 5), 'Submit Draft', 'Review Notes'];
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
    
    // Create some activities
    Activity::create([
        'user_id' => $user->id,
        'assignment_id' => $assignment->id,
        'action' => 'created_assignment',
        'description' => "created assignment {$assignment->name}",
        'created_at' => now()->subDays(rand(1, 5)),
    ]);
}

echo "Seeded data for {$email} successfully!\n";

<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\Task;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class NotifyUpcomingDeadlines extends Command
{
    protected $signature = 'notifications:deadlines';
    protected $description = 'Notify assigned users about tasks due within the next 2 days';

    public function handle(NotificationService $notifications): int
    {
        $dueFrom = now()->startOfDay();
        $dueTo = now()->addDays(2)->endOfDay();

        $tasks = Task::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$dueFrom, $dueTo])
            ->with('assignment.members', 'assignedTo')
            ->get();

        $count = 0;

        foreach ($tasks as $task) {
            if (!$task->assignedTo) {
                continue;
            }

            $notifications->notify(
                $task->assignedTo,
                'deadline_near',
                [
                    'title' => 'Deadline approaching',
                    'message' => "Task '{$task->title}' in {$task->assignment->name} is due soon.",
                    'assignment_id' => $task->assignment_id,
                    'task_id' => $task->id,
                ]
            );

            $count++;
        }

        $this->info("Queued {$count} deadline reminder(s).");

        return self::SUCCESS;
    }
}

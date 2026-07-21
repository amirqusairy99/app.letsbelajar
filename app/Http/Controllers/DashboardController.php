<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $user = $request->user();
        
        $myAssignments = Assignment::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->withCount('tasks')->latest()->get();

        $upcomingDeadlines = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->startOfDay();
        $overdueTasks = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', '!=', 'completed')
            ->where('due_date', '<', $today)
            ->get();

        $tasksDueToday = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereDate('due_date', $today)
            ->where('status', '!=', 'completed')
            ->get();

        $tasksDueTomorrow = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereDate('due_date', $tomorrow)
            ->where('status', '!=', 'completed')
            ->get();

        $pendingTasks = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', '!=', 'completed')->count();

        $completedTasks = Task::whereHas('assignment.members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'completed')->count();

        $recentActivities = Activity::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'myAssignments',
            'upcomingDeadlines',
            'overdueTasks',
            'tasksDueToday',
            'tasksDueTomorrow',
            'pendingTasks',
            'completedTasks',
            'recentActivities'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Assignment;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $now = now();
        $startOfToday = $now->copy()->startOfDay();
        $startOfThisWeek = $now->copy()->startOfWeek();

        $totalUsers = User::count();
        $newUsersToday = User::where('created_at', '>=', $startOfToday)->count();
        $newUsersThisWeek = User::where('created_at', '>=', $startOfThisWeek)->count();

        $totalAssignments = Assignment::count();
        $totalWorkspaces = Assignment::distinct('created_by')->count('created_by');

        $totalFileUploads = File::count();
        $storageUsedBytes = (float) File::sum('size');

        $activeUserIds = DB::table('sessions')
            ->where('last_activity', '>=', $startOfToday->timestamp)
            ->distinct()
            ->pluck('user_id')
            ->filter();
        $activeUsersToday = $activeUserIds->count();

        $recentActivity = Activity::with('user')
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday',
            'newUsersThisWeek',
            'totalWorkspaces',
            'totalAssignments',
            'totalFileUploads',
            'storageUsedBytes',
            'activeUsersToday',
            'recentActivity'
        ));
    }

    public function users(Request $request)
    {
        $users = User::orderByDesc('created_at')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function toggleUser(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'You cannot disable an admin account.');
        }

        $user->update([
            'is_disabled' => ! $user->is_disabled,
            'disabled_at' => ! $user->is_disabled ? now() : null,
        ]);

        $status = $user->is_disabled ? 'disabled' : 'enabled';

        return back()->with('success', "User {$user->name} has been {$status}.");
    }
}

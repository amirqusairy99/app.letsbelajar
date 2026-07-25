<?php

use App\Http\Controllers\SuspendedController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/account/suspended', [SuspendedController::class, 'show'])->name('account.suspended');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('assignments', AssignmentController::class);
    Route::post('/assignments/{assignment}/archive', [AssignmentController::class, 'archive'])->name('assignments.archive');
    Route::post('/assignments/{assignment}/unarchive', [AssignmentController::class, 'unarchive'])->name('assignments.unarchive');
    
    Route::post('/assignments/{assignment}/members', [MemberController::class, 'store'])->name('members.store');
    Route::delete('/assignments/{assignment}/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::patch('/assignments/{assignment}/members/{member}', [MemberController::class, 'update'])->name('members.update');
    
    Route::get('/assignments/{assignment}/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/assignments/{assignment}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/assignments/{assignment}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/assignments/{assignment}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/assignments/{assignment}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    Route::get('/kanban', [KanbanController::class, 'board'])->name('kanban.board');
    Route::post('/tasks/{task}/move', [KanbanController::class, 'move'])->name('tasks.move');
    Route::get('/assignments/{assignment}/kanban', [KanbanController::class, 'index'])->name('kanban.index');
    
    Route::get('/assignments/{assignment}/files', [FileController::class, 'index'])->name('files.index');
    Route::post('/assignments/{assignment}/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::get('/assignments/{assignment}/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::get('/assignments/{assignment}/files/{file}/preview', [FileController::class, 'preview'])->name('files.preview');
    Route::get('/assignments/{assignment}/files/{file}/content', [FileController::class, 'content'])->name('files.content');
    Route::patch('/assignments/{assignment}/files/{file}', [FileController::class, 'update'])->name('files.update');
    Route::delete('/assignments/{assignment}/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    
    Route::post('/assignments/{assignment}/folders', [FileController::class, 'createFolder'])->name('folders.store');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    });
});

require __DIR__.'/auth.php';

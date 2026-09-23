<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/assignments', [AssignmentController::class, 'index']);
    Route::post('/assignments', [AssignmentController::class, 'store']);
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show']);
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update']);
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy']);
    Route::post('/assignments/{assignment}/archive', [AssignmentController::class, 'archive']);
    Route::post('/assignments/{assignment}/unarchive', [AssignmentController::class, 'unarchive']);
    Route::get('/assignments/{assignment}/analytics', [AssignmentController::class, 'analytics']);

    Route::get('/assignments/{assignment}/members', [MemberController::class, 'index']);
    Route::post('/assignments/{assignment}/members', [MemberController::class, 'store']);
    Route::put('/assignments/{assignment}/members/{member}', [MemberController::class, 'update']);
    Route::delete('/assignments/{assignment}/members/{member}', [MemberController::class, 'destroy']);

    Route::get('/assignments/{assignment}/tasks', [TaskController::class, 'index']);
    Route::post('/assignments/{assignment}/tasks', [TaskController::class, 'store']);
    Route::put('/assignments/{assignment}/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/assignments/{assignment}/tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('/tasks/{task}/move', [TaskController::class, 'move']);

    Route::get('/calendar/events', [CalendarController::class, 'events']);

    Route::get('/assignments/{assignment}/files', [FileController::class, 'index']);
    Route::get('/assignments/{assignment}/folders', [FileController::class, 'folders']);
    Route::post('/assignments/{assignment}/files/upload', [FileController::class, 'upload']);
    Route::get('/assignments/{assignment}/files/{file}/download', [FileController::class, 'download']);
    Route::get('/assignments/{assignment}/files/{file}/content', [FileController::class, 'content']);
    Route::delete('/assignments/{assignment}/files/{file}', [FileController::class, 'destroy']);
    Route::post('/assignments/{assignment}/folders', [FileController::class, 'createFolder']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);

    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'changePassword']);
    Route::get('/users/search', [ProfileController::class, 'search']);
});

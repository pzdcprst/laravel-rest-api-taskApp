<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\StatisticController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:login')->group(function () {
        Route::post('auth/login', [AuthController::class, 'login']);
        Route::post('auth/register', [AuthController::class, 'register']);
    });

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::apiResource('projects', ProjectController::class);

        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
        Route::patch('tasks/{task}/due_date', [TaskController::class, 'setDueDate']);

        Route::apiResource('projects/{project}/tasks', TaskController::class)->only(['store']);
        Route::apiResource('tasks', TaskController::class)->except(['store']);

        Route::get('statistics/tasks', [StatisticController::class, 'statistics']);

        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
    });
});
<?php

namespace App\Services;

use App\Models\User;

class StatisticService
{
    public function getStatistics(User $user)
    {
        $totalTasks = $user->tasks()->count();

        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $pendingTasks = $user->tasks()->where('status', 'pending')->count();
        $inProgressTasks = $user->tasks()->where('status', 'in_progress')->count();
        $canceledTasks = $user->tasks()->where('status', 'canceled')->count();
        
        $lowPriorityTasks = $user->tasks()->where('priority', 'low')->count();
        $mediumPriorityTasks = $user->tasks()->where('priority', 'medium')->count();
        $highPriorityTasks = $user->tasks()->where('priority', 'high')->count();

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'in_progress_tasks' => $inProgressTasks,
            'canceled_tasks' => $canceledTasks,
            'low_priority_tasks' => $lowPriorityTasks,
            'medium_priority_tasks' => $mediumPriorityTasks,
            'high_priority_tasks' => $highPriorityTasks,
        ];
    }
}
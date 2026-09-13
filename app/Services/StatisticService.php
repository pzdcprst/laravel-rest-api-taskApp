<?php

namespace App\Services;

use App\Models\User;

class StatisticService
{
    private function statusStatistics(User $user)
    {
        return [
            'completed' => $user->tasks()->where('status', 'completed')->count(),
            'pending' => $user->tasks()->where('status', 'pending')->count(),
            'in_progress' => $user->tasks()->where('status', 'in_progress')->count(),
            'canceled' => $user->tasks()->where('status', 'canceled')->count(),
        ];
    }

    private function priorityStatistics(User $user)
    {
        return [
            'low' => $user->tasks()->where('priority', 'low')->count(),
            'medium' => $user->tasks()->where('priority', 'medium')->count(),
            'high' => $user->tasks()->where('priority', 'high')->count(),
        ];
    }

    private function checkOverdueTasks(User $user)
    {
        return $user->tasks()
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'canceled'])
            ->count();
    }

    public function getStatistics(User $user)
    {
        return [
            'total' => $user->tasks()->count(),
            'by_status' => $this->statusStatistics($user),
            'by_priority' => $this->priorityStatistics($user),
            'overdue' => $this->checkOverdueTasks($user),
        ];
    }
}

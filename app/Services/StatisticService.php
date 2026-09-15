<?php

namespace App\Services;

use App\Models\User;
use App\Enums\Status;
use App\Enums\Priority;
use Illuminate\Validation\Rules\Enum;

class StatisticService
{
    private function groupByRequest(User $user, string $column)
    {
        return $user->tasks()
            ->selectRaw("$column, COUNT(*) as count")
            ->groupBy($column)
            ->get()
            ->pluck('count', $column)
            ->toArray();
    }
    
    private function statusStatistics(User $user, string $column = 'status')
    {
        $countByStatus = $this->groupByRequest($user, $column);

        return [
            'pending' => $countByStatus['pending'] ?? 0,
            'in_progress' => $countByStatus['in_progress'] ?? 0,
            'completed' => $countByStatus['completed'] ?? 0,
            'canceled' => $countByStatus['canceled'] ?? 0,
        ];
    }

    private function priorityStatistics(User $user, string $column = 'priority')
    {
        $countByPriority = $this->groupByRequest($user, $column);

        return [
            'low' => $countByPriority['low'] ?? 0,
            'medium' => $countByPriority['medium'] ?? 0,
            'high' => $countByPriority['high'] ?? 0,
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

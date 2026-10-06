<?php

namespace App\Services;

use App\Enums\Status;
use App\Exceptions\TaskExceptions\InvalidTaskStatusTransitionException;
use App\Exceptions\TaskExceptions\TaskAlreadyCompletedException;
use App\Exceptions\TaskExceptions\TaskCannotBeCancelledException;
use App\Models\Task;

class TaskStatusService
{
    public function changeStatus(Task $task, string $newStatus): Task
    {
        $current = $task->status?->value ?? $task->status;

        if ($current === Status::completed->value && $newStatus !== Status::completed->value) {
            throw new TaskAlreadyCompletedException;
        }

        if ($newStatus === Status::cancelled->value && $current === Status::completed->value) {
            throw new TaskCannotBeCancelledException;
        }

        if ($newStatus === Status::pending->value && $current === Status::completed->value) {
            throw new InvalidTaskStatusTransitionException(
                Status::completed->value,
                Status::pending->value
            );
        }

        $task->update([
            'status' => $newStatus,
        ]);

        return $task->refresh();
    }
}

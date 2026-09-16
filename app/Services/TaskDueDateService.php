<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Date;
use App\Enums\Status;
use App\Exceptions\DueDateException\InvalidDueDateException;
use App\Exceptions\TaskExceptions\TaskAlreadyCompletedException;
use Carbon\Carbon;

class TaskDueDateService
{
    public function updateDueDate(Task $task, Carbon $dueDate): Task
    {
        if ($task->status === Status::completed->value) {
            throw new TaskAlreadyCompletedException();
        }

        if ($dueDate->startOfDay()->lt(Carbon::today())) {
            throw new InvalidDueDateException();
        }

        $task->update([
            'due_date' => $dueDate,
        ]);

        return $task->refresh();
    }
}
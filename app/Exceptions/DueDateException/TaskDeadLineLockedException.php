<?php

namespace App\Exceptions\DueDateException;

use App\Exceptions\DomainException;

class TaskDeadLineLockedException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            'Due date cannot be updated because task is already completed',
            409,
            'task_dead_line_locked'
        );
    }
}
<?php

namespace App\Exceptions\TaskExceptions;

use Exception;
use App\Exceptions\DomainException;

class TaskAlreadyCompletedException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            "Task is already completed",
            409,
            'task_already_completed',
        );
    }
}

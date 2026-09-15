<?php

namespace App\Exceptions\TaskExceptions;

use App\Exceptions\DomainException;
use Exception;


class TaskCannotBeCancelledException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            "Task is already done and cannot be cancelled",
            409,
            'task_cannot_be_cancelled',
        );
    }
}

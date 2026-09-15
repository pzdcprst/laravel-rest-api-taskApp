<?php

namespace App\Exceptions\TaskExceptions;

use App\Exceptions\DomainException;
use Exception;

class InvalidTaskStatusTransitionException extends DomainException
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(
            "You cannot transition task from '{$from}' to '{$to}'",
            409,
            'invalid_task_status_transition',
            [
                'from' => $from,
                'to' => $to,
            ]
        );
    }
}

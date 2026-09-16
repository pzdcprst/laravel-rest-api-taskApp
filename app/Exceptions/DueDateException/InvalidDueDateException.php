<?php

namespace App\Exceptions\DueDateException;

use App\Exceptions\DomainException;

class InvalidDueDateException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            "Invalid due date",
            409,
            'invalid_due_date'
        );
    }
}
<?php

namespace App\Exceptions;

use Exception;

class DomainException extends Exception
{
    public function __construct(
        string $message, 
        protected int $status = 400, 
        protected string $errorCode = 'domain_error',
        protected array $errors = [],
        ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}

<?php

namespace App\Enums;

enum Status : string
{
    case pending = 'pending';
    case inProgress = 'in_progress';
    case completed = 'completed';
    case cancelled = 'cancelled';

    public static function values() : array
    {
        return array_column(self::cases(), 'value');
    }
}

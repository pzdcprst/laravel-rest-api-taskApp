<?php

namespace App\Enums;

enum Role: string
{
    case admin = 'admin';
    case moderator = 'moderator';
    case user = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
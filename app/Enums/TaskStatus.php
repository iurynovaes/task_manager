<?php

namespace App\Enums;

class TaskStatus
{
    const OPEN = 'open';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';
    const REJECTED = 'rejected';

    public static function isValid($value)
    {
        return in_array($value, self::getValues());
    }

    public static function getValues()
    {
        return [
            self::OPEN,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::REJECTED,
        ];
    }
}

<?php

namespace App\Enums;

class BooleanAnswer
{
    public const NO = 0;
    public const YES = 1;

    public const STATUS_TEXT = [
        self::NO => 'YES',
        self::YES => 'NO',
    ];

    public const STATUS_CSS_CONTEXT_CLASS = [
        self::NO => 'not-flagged',
        self::YES => 'is-flagged',
    ];

    public function getText($value)
    {

    }
}

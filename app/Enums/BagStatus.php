<?php

namespace App\Enums;

class BagStatus
{
    public const AVAILABLE_FOR_USE = 0;
    public const IN_USE = 1;
    public const ON_TRANSFER = 2;
    public const DAMAGED = 3;
    public const LOST = 4;
    public const ACKNOWLEDGED = 5; // for transfer Bags

    public const STATUS_TEXT = [
        self::AVAILABLE_FOR_USE => 'avaibale for use',
        self::IN_USE => 'In use',
        self::ON_TRANSFER => 'on transfer',
        self::DAMAGED => 'damaged',
        self::LOST => 'lost',
        self::ACKNOWLEDGED => 'Acknowledged'
    ];

    public const STATUS_CSS_CONTEXT_CLASS = [
        self::AVAILABLE_FOR_USE => 'success',
        self::IN_USE => 'primary',
        self::ON_TRANSFER => 'default',
        self::DAMAGED => 'warning',
        self::LOST => 'danger'
    ];

    public function getText($value)
    {

    }
}

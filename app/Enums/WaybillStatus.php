<?php

namespace App\Enums;

class WaybillStatus
{
    public const IN_TRANSIT = 0;
    public const ACKNOWLEDGED = 1;
    public const PENDING = 2;
    public const CANCELLED = 3;

    public const STATUS_TEXT = [
        self::IN_TRANSIT => 'In Transit',
        self::ACKNOWLEDGED => 'Acknowledged',
        self::CANCELLED => 'Cancelled'
    ];

    public const STATUS_CSS_CONTEXT_CLASS = [
        self::IN_TRANSIT => 'default',
        self::ACKNOWLEDGED => 'success',
        self::PENDING => 'red',
        self::CANCELLED => 'warning',
    ];

    public function getText($value)
    {

    }
}

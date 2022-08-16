<?php

namespace App\Enums;

class ManifestStatus
{
    public const IN_TRANSIT = 0;
    public const ACKNOWLEDGED = 1;
    public const CANCELLED = 2;
    public const PARTIALLY_RECEIVED = 3;

    public const STATUS_TEXT = [
        self::IN_TRANSIT => 'In Transit',
        self::ACKNOWLEDGED => 'Acknowledged',
        self::PARTIALLY_RECEIVED => 'Partially Acknowledged',
        self::CANCELLED => 'Cancelled'
    ];

    public const STATUS_CSS_CONTEXT_CLASS = [
        self::IN_TRANSIT => 'default',
        self::ACKNOWLEDGED => 'success',
        self::CANCELLED => 'warning',
        self::PARTIALLY_RECEIVED => 'gray'
    ];

    public function getText($value)
    {

    }
}

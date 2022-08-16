<?php

namespace App\Enums;

class BagType
{
    public const DC_AND_DC = 0;
    public const DC_AND_WHAREHOUSE = 1;

    public const TYPE_TEXT = [
        self::DC_AND_DC => 'DCDC',
        self::DC_AND_WHAREHOUSE => 'WHDC',
    ];

    public const STATUS_CSS_CONTEXT_CLASS = [
        self::DC_AND_DC => 'default',
        self::DC_AND_WHAREHOUSE => 'success',
    ];

    public function getText($value)
    {

    }
}

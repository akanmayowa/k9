<?php

namespace App\Enums;

class TarriffZone
{
    public const ZONE_ONE = 1;
    public const ZONE_TWO = 2;
    public const ZONE_THREE = 3;
    public const ZONE_FOUR = 4;

    public const ZONE_TEXT = [
        self::ZONE_ONE => 'Zone 1',
        self::ZONE_TWO => 'Zone 2',
        self::ZONE_THREE => 'Zone 3',
        self::ZONE_FOUR => 'Zone 4'
    ];

    public function getText($value)
    {

    }
}

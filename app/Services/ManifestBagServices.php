<?php

namespace App\Services;

use App\ManifestBag;
use Illuminate\Support\Str;

class ManifestBagServices
{
    public static function sealNumberExists($seal_number)
    {
        //Recall unkor ?
        return ManifestBag::where('seal_number', $seal_number)->exists();
    }
}

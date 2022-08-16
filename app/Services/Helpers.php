<?php

namespace App\Services;

use App\ManifestBag;
use Illuminate\Support\Str;

class Helpers
{
    public static function sealNumberExists($seal_number)
    {
       return ManifestBag::where('seal_number', $seal_number)->count();
    }


    public static function generateCode($length = 5)
    {
        //Default password length ?
        //is length grether than the length of the generated code ?
        $code = Str::uuid()->toString();
        return Str::substr($code, 0, $length);
    }



}

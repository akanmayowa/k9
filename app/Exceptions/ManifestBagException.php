<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ManifestBagException extends Exception
{
    public function report()
    {
        Log::debug("Error , Could Not create manifest!");
    }
}



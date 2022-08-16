<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class BagException extends Exception
{
    public function report()
    {
        Log::debug("Bag Error");
    }
}

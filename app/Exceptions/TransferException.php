<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class TransferException extends Exception
{
    public function report()
    {
        Log::debug("Transfer Error");
    }
}

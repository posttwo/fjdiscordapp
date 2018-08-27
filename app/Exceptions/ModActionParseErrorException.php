<?php

namespace App\Exceptions;

use Exception;
use Log;

class ModActionParseErrorException extends Exception
{
    public function report()
    {
        Log::channel('slack')->emergency('FJMEME PARSER ERROR');
    }
}

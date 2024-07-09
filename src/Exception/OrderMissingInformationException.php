<?php

namespace App\Exception;

use Exception;

class OrderMissingInformationException extends Exception
{
    public function __construct(string $message = "Missing information", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

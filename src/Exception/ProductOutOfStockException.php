<?php

namespace App\Exception;

use Exception;

class ProductOutOfStockException extends Exception
{
    public function __construct(string $message = "Product out of stock", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

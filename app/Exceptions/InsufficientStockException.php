<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(string $message = 'Stok barang tidak mencukupi.')
    {
        parent::__construct($message);
    }
}

<?php

declare(strict_types=1);

namespace App\Exception;

use Exception as Exception;

class KeyNotFoundException extends Exception
{
    public function __construct(string $key)
    {
        parent::__construct("Key not fount: $key", 0, null);
    }
}

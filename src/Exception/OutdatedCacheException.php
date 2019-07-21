<?php

declare(strict_types=1);

namespace App\Exception;

use Exception as Exception;

class OutdatedCacheException extends Exception
{
    public function __construct(string $key, float $time)
    {
        parent::__construct("Outdated cache: key: $key, time: $time", 0, null);
    }
}

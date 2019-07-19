<?php

declare(strict_types=1);

namespace App;

use Exception as Exception;

class CacheException extends Exception
{
    const POOL_EMPTY = 'exception message: pool is empty';
}

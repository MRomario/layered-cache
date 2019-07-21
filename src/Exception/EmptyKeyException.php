<?php

declare(strict_types=1);

namespace App\Exception;

use Exception as Exception;

class EmptyKeyException extends Exception
{
    public function __construct()
    {
        parent::__construct('empty key', 0, null);
    }
}

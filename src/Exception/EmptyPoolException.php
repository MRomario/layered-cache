<?php

declare(strict_types=1);

namespace App\Exception;

use Exception as Exception;

class EmptyPoolException extends Exception
{
    public function __construct()
    {
        parent::__construct('Pool is empty', 0, null);
    }
}

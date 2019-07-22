<?php

declare(strict_types=1);

namespace App\Traits;

use App\Exception\EmptyKeyException;

trait EmptyKeyExceptionTrait
{
    /**
     * @param string $key
     *
     * @throws EmptyKeyException
     */
    public function checkIsEmptyKeyException(string $key)
    {
        if ('' === trim($key)) {
            throw new EmptyKeyException();
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Traits;

use App\Exception\OutdatedCacheException;

trait OutdatedCacheExceptionTrait
{
    /**
     * @param $key
     * @param $diffTtl
     *
     * @throws OutdatedCacheException
     */
    public function checkOutdatedCacheKey($key, $diffTtl)
    {
        if ($diffTtl >= 0) {
            throw new OutdatedCacheException($key, $diffTtl);
        }
    }
}

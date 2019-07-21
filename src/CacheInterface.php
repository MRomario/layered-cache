<?php

declare(strict_types=1);

namespace App;

use App\Exception\EmptyKeyException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;

interface CacheInterface
{
    /**
     * @param string $key
     *
     * @throws KeyNotFoundException;
     * @throws OutdatedCacheException;
     * @throws EmptyKeyException
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     *
     * @throws EmptyKeyException
     *
     * @return mixed
     */
    public function set(string $key, $value, $ttl = 3600);
}

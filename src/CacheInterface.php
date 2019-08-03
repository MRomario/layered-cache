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
     * @return mixed
     *
     * @throws OutdatedCacheException;
     * @throws EmptyKeyException
     * @throws KeyNotFoundException;
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     */
    public function set(string $key, $value, $ttl = 3600): void;

    public function clear();
}

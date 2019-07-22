<?php

namespace App;

use App\Exception\EmptyKeyException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;

class FileCache implements CacheInterface
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
    public function get(string $key)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     *
     * @return mixed
     *
     * @throws EmptyKeyException
     */
    public function set(string $key, $value, $ttl = 3600)
    {
        // TODO: Implement set() method.
    }
}

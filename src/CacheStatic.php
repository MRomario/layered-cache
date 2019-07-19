<?php

declare(strict_types=1);

namespace App;

class CacheStatic implements CacheInterface
{
    /**
     * @param array
     *
     * @return array
     */
    protected $data;

    /**
     * @param string $key
     *
     * @return mixed
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
     */
    public function set(string $key, $value, $ttl = 3600)
    {
        // TODO: Implement set() method.
    }
}

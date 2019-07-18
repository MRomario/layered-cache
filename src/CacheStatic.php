<?php

declare(strict_types=1);

namespace App;

class CacheStatic implements CacheInterface
{
    public $data;

    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key];
    }
}

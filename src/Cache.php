<?php

declare(strict_types=1);

namespace App;

use App\CacheException as CacheException;

class Cache implements CacheInterface, CachePoolInterface
{
    /**
     * @param array $pool
     *
     * @return array
     */
    protected $pool;

    /**
     * Get key from pool.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \App\CacheException
     */
    public function get(string $key)
    {
        if (!$this->pool) {
            throw new CacheException(CacheException::POOL_EMPTY);
        }

        foreach ($this->pool as $layerCache) {
            $value = $layerCache->get($key);

            return (!$value) ?: $value;
        }
    }

    /**
     * Set key to poll.
     *
     * @param string $key
     * @param $value
     * @param int $ttl
     *
     * @throws \App\CacheException
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        if (!$this->pool) {
            throw new CacheException(CacheException::POOL_EMPTY);
        }

        foreach ($this->pool as $layerCache) {
            $layerCache->set($key);
        }
    }

    /**
     * Add cache layer to poll.
     *
     * @param CacheInterface $layer
     */
    public function addLayer(CacheInterface $layer): void
    {
        $this->pool[] = $layer;
    }
}

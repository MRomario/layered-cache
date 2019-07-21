<?php

declare(strict_types=1);

namespace App;

use App\Exception\EmptyKeyException;
use App\Exception\EmptyPoolException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;

class Cache implements CacheLayerInterface
{
    /**
     * @var CacheLayerInterface[]
     */
    protected $layers = [];

    /**
     * {@inheritdoc}
     *
     * @throws EmptyKeyException
     * @throws EmptyPoolException
     */
    public function get(string $key)
    {
        if ('' === trim($key)) {
            throw new EmptyKeyException();
        }

        if (empty($this->layers)) {
            throw new EmptyPoolException();
        }

        foreach ($this->layers as $layer) {
            try {
                $value = $layer->get($key);
            } catch (KeyNotFoundException | OutdatedCacheException $e) {
                continue;
            }

            return $value;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws EmptyKeyException
     * @throws EmptyPoolException
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        if ('' === trim($key)) {
            throw new EmptyKeyException();
        }

        if (empty($this->layers)) {
            throw new EmptyPoolException();
        }

        foreach ($this->layers as $layer) {
            $layer->set($key, $value, $ttl);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addLayer(CacheInterface $layer): void
    {
        $this->layers[] = $layer;
    }
}

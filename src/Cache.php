<?php

declare(strict_types=1);

namespace App;

use App\Exception\EmptyPoolException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;
use App\Traits\EmptyKeyExceptionTrait;

class Cache implements CacheLayerInterface
{
    use EmptyKeyExceptionTrait;
    /**
     * @var CacheLayerInterface[]
     */
    protected $layers = [];

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $this->checkIsEmptyKeyException($key);
        $this->checkIsNotEmptyPool();

        $outdatedKey = false;
        foreach ($this->layers as $layer) {
            try {
                return $layer->get($key);
            } catch (KeyNotFoundException $e) {
                continue;
            } catch (OutdatedCacheException $e) {
                $outdatedKey = true;
            }
        }
        throw $outdatedKey ? new OutdatedCacheException($key) : new KeyNotFoundException($key);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->checkIsEmptyKeyException($key);
        $this->checkIsNotEmptyPool();

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

    private function checkIsNotEmptyPool()
    {
        if (empty($this->layers)) {
            throw new EmptyPoolException();
        }
    }

    public function clear(): void
    {
        $this->checkIsNotEmptyPool();

        foreach ($this->layers as $layer) {
            $layer->clear();
        }
    }
}

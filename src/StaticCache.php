<?php

declare(strict_types=1);

namespace App;

use App\Exception\KeyNotFoundException;
use App\Traits\EmptyKeyExceptionTrait;
use App\Traits\OutdatedCacheExceptionTrait;

class StaticCache implements CacheInterface
{
    use EmptyKeyExceptionTrait;
    use OutdatedCacheExceptionTrait;
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var float[]
     */
    protected $ttl = [];

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $this->checkIsEmptyKeyException($key);

        if (!array_key_exists($key, $this->data)) {
            throw  new KeyNotFoundException($key);
        }

        $diffTtl = (microtime(true) - $this->ttl[$key]);

        $this->checkOutdatedCacheKey($key, $diffTtl);

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->checkIsEmptyKeyException($key);
        $this->ttl[$key] = microtime(true) + $ttl;
        $this->data[$key] = $value;
    }

    public function clear(): void
    {
        $this->data = [];
    }
}

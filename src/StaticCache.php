<?php

declare(strict_types=1);

namespace App;

use App\Exception\EmptyKeyException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;

class StaticCache implements CacheInterface
{
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
        if ('' === trim($key)) {
            throw new EmptyKeyException();
        }

        if (!array_key_exists($key, $this->data)) {
            throw  new KeyNotFoundException($key);
        }

        $diffTtl = (microtime(true) - $this->ttl[$key]);
        if ($diffTtl >= 0) {
            throw new OutdatedCacheException($key, $diffTtl);
        }

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        if ('' === trim($key)) {
            throw new EmptyKeyException();
        }

        $this->ttl[$key] = microtime(true) + $ttl;
        $this->data[$key] = $value;
    }
}

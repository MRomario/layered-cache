<?php

declare(strict_types=1);

namespace App;

interface CacheInterface
{
    public function get(string $key);

    public function set(string $key, $value, $ttl = 3600);
}

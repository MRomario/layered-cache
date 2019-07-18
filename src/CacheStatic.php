<?php

declare(strict_types=1);

namespace App;

class CacheStatic
{
    public $data;

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->data[$key];
    }
}

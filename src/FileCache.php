<?php

namespace App;

use App\Exception\KeyNotFoundException;
use App\Traits\EmptyKeyExceptionTrait;
use App\Traits\OutdatedCacheExceptionTrait;

class FileCache implements CacheInterface
{
    use EmptyKeyExceptionTrait;
    use OutdatedCacheExceptionTrait;

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $this->checkIsEmptyKeyException($key);

        $file = $this->getFile($key);
        if (!file_exists($file)) {
            throw new KeyNotFoundException($key);
        }

        $diffTtl = microtime(true) - filemtime($file);

        $this->checkOutdatedCacheKey($key, $diffTtl);

        return file_get_contents($file);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, $ttl = 3600, $limitCache = 5): void
    {
        $this->checkIsEmptyKeyException($key);

        $timeLifeFile = microtime(true) + $ttl;
        $file = $this->getFile($key);
        file_put_contents($file, $value);
        touch($file, $timeLifeFile);
    }

    /**
     * @param $key
     *
     * @return string
     */
    private function getFile($key): string
    {
        return sys_get_temp_dir().DIRECTORY_SEPARATOR.md5($key).'.cache';
    }

    public function clear(): void
    {
        $files = glob(sys_get_temp_dir().DIRECTORY_SEPARATOR.'*.cache');
        foreach ($files as $file) {
            (!is_file($file)) ?: unlink($file);
        }
    }
}

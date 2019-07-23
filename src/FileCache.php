<?php

namespace App;

use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;
use App\Traits\EmptyKeyExceptionTrait;

class FileCache implements CacheInterface
{
    use EmptyKeyExceptionTrait;

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $this->checkIsEmptyKeyException($key);

        $file = $this->getPathFile($key);
        if (!file_exists($file)) {
            throw new KeyNotFoundException($key);
        }

        $diffTtl = microtime(true) - filemtime($file);
        if ($diffTtl >= 0) {
            throw new OutdatedCacheException($key);
        }

        return file_get_contents($file);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->checkIsEmptyKeyException($key);

        $timeLifeFile = microtime(true) + $ttl;
        $file = $this->getPathFile();
        file_put_contents($file, $value);
        touch($file, $timeLifeFile);
    }

    public function deleteAllKeysCache(): void
    {
        $files = glob(sys_get_temp_dir().DIRECTORY_SEPARATOR.'*.cache');
        foreach ($files as $file) {
            (!is_file($file)) ?: unlink($file);
        }
    }

    /**
     * @param $key
     *
     * @return string
     */
    private function getPathFile($key): string
    {
        return sys_get_temp_dir().DIRECTORY_SEPARATOR.md5($key).'.cache';
    }
}

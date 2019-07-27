<?php

namespace App;

use App\Exception\KeyNotFoundException;
use App\Traits\EmptyKeyExceptionTrait;
use App\Traits\OutdatedCacheExceptionTrait;

class FileCache implements CacheInterface, LimitedSizeInterface
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

        $allCacheFiles = $this->getAllCacheFiles();

        if (count($allCacheFiles) >= $limitCache) {
            $tempArray = [];
            foreach ($allCacheFiles as $fileCheck) {
                $tempArray[$fileCheck] = filemtime($fileCheck);
            }
            unlink(array_keys($tempArray, min($tempArray))[0]);
        }

        $file = $this->getFile($key);
        file_put_contents($file, $value, LOCK_EX);
        touch($file, microtime(true) + $ttl);
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        // TODO: Implement setSize() method.
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

    /**
     * @return array
     */
    private function getAllCacheFiles(): array
    {
        $allFiles = glob(sys_get_temp_dir().DIRECTORY_SEPARATOR.'*.cache');

        return ($allFiles) ? $allFiles : [];
    }

    public function clear(): void
    {
        foreach ($this->getAllCacheFiles() as $file) {
            (!is_file($file)) ?: unlink($file);
        }
    }
}

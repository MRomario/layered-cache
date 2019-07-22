<?php

namespace App;

use App\Exception\KeyNotFoundException;
use App\Traits\EmptyKeyExceptionTrait;

class FileCache implements CacheInterface
{
    use EmptyKeyExceptionTrait;

    /**
     * @var string
     */
    protected $cacheFolder;
    protected $ttlFile;

    /**
     * FileCache constructor.
     */
    public function __construct()
    {
        $this->cacheFolder = __DIR__.DIRECTORY_SEPARATOR.'cache';
        $this->ttlFile = __DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'ttl.txt';
        $this->checkExistsCacheFolder();
        $this->checkExistsTtlFile();
    }

    /**
     * @param string $key
     *
     * @return mixed
     *
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $this->checkIsEmptyKeyException($key);

        $cacheKey = md5($key);
        $keyFile = $this->cacheFolder.DIRECTORY_SEPARATOR.$cacheKey;
        $diffTtl = (microtime(true) - $this->getDataTtlFile()[$cacheKey]);

        if (!file_exists($keyFile)) {
            throw new KeyNotFoundException($key);
        }

        return file_get_contents($keyFile);
    }

    /**
     * Set new key: create new cache file.
     *
     * @param string $key
     * @param $value
     * @param int $ttl
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->checkIsEmptyKeyException($key);

        $keyCache = md5($key);

        $dataTtl = $this->getDataTtlFile();
        $dataTtl[$keyCache] = microtime(true) + $ttl;
        $this->setDataTtlFile($dataTtl);

        $file = $this->cacheFolder.DIRECTORY_SEPARATOR.$keyCache;
        file_put_contents($file, $value);
    }

    /**
     * Deleted all keys from cache folder.
     */
    public function deleteAllKeysCache(): void
    {
        $files = glob($this->cacheFolder.DIRECTORY_SEPARATOR.'*');
        foreach ($files as $file) {
            (!is_file($file)) ?: unlink($file);
        }
    }

    /**
     * Check existing folder fore cache files.
     */
    protected function checkExistsCacheFolder(): void
    {
        if (!file_exists($this->cacheFolder)) {
            mkdir($this->cacheFolder, 0777, true);
        }
    }

    /**
     * Check existing ttl_file (serialize array).
     */
    protected function checkExistsTtlFile(): void
    {
        if (!file_exists($this->ttlFile)) {
            $this->setDataTtlFile([]);
        }
    }

    /**
     * Save data to ttl file : serialize array.
     *
     * @param array $data
     */
    protected function setDataTtlFile(array $data): void
    {
        file_put_contents($this->ttlFile, serialize($data));
    }

    /**
     * Get data from ttl file.
     *
     * @return array
     */
    protected function getDataTtlFile(): array
    {
        return unserialize(file_get_contents($this->ttlFile));
    }
}

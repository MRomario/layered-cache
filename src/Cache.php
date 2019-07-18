<?php

declare(strict_types=1);

namespace App;

class Cache implements CacheInterface
{
    /**
     * @var array - array all storages
     */
    public $storage;
    /**
     * @var array - array storages with out keys
     */
    public $emptyStorages;

    /**
     * @param object storage - new layer storage
     */
    public function addStorage(object $layer): void
    {
        $this->storage[] = $layer;
    }

    /**
     * Method set key->value storage.
     *
     * @param string $key   - key value
     * @param mixed  $value - value
     * @param int    $ttl
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->setKey($key, $value, $ttl);

        return;
    }

    /**
     * Method get key->value from storage.
     *
     * @param string $key - key value
     *
     * @return mixed - value
     */
    public function get(string $key)
    {
        $value = $this->getKey($key);
        (empty($this->emptyStorages)) ?: $this->checkEmptyStorages($key, $value);

        return $value;
    }

    /**
     * Method rewrite key->value to all empty storage.
     *
     * @param string $key   - key value
     * @param mixed  $value - value
     */
    public function checkEmptyStorages($key, $value): void
    {
        if ($value) {
            if ($this->emptyStorages) {
                foreach ($this->emptyStorages as $storage) {
                    $storage->set($key, $value);
                }
            }
            $this->emptyStorages = [];
        }

        return;
    }

    /**
     * Method search key->value from all storage.
     *
     * @param string $key - key value
     *
     * @return mixed value
     */
    public function getKey(string $key)
    {
        foreach ($this->storage as $layer) {
            $value = $layer->get($key);
            if ($value) {
                return $value;
            }
            $this->emptyStorages[] = $layer;
        }

        return $value;
    }

    /**
     * Method set key->value to all storage.
     *
     * @param string $key   - key value
     * @param mixed  $value - value
     * @param int    $ttl
     */
    public function setKey(string $key, $value, $ttl): void
    {
        foreach ($this->storage as $layer) {
            $layer->set($key, $value, $ttl);
        }

        return;
    }
}

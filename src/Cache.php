<?php

namespace App;

class Cache
{
    public $storage;
    public $emptyStorage;

    /**
     * @param object - new layer storage
     */
    public function addStorage(object $storage): void
    {
        $this->storage[] = $storage;
    }

    /**
     * @param string $key   - key value
     * @param mixed  $value - value
     * @param int    $ttl
     */
    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->setKey($key, $value, $ttl);
    }

    /**
     * @param string $key - key value
     *
     * @return mixed - value
     */
    public function get(string $key)
    {
        $value = $this->getKey($key);
        (empty($this->emptyStorage)) ?: $this->checkEmptyStorage($key, $value);

        return $value;
    }

    /**
     * Method rewrite key->value to empty storage.
     *
     * @param string $key   - key value
     * @param mixed  $value - value
     */
    public function checkEmptyStorage($key, $value): void
    {
        if ($value) {
            if ($this->emptyStorage) {
                foreach ($this->emptyStorage as $storage) {
                    $storage->set($key, $value);
                }
            }
            $this->emptyStorage = [];
        }
    }

    /**
     * Method search key->value from all layers.
     *
     * @param string $key - key value
     *
     * @return mixed
     */
    public function getKey(string $key)
    {
        $value = null;
        foreach ($this->storage as $storage) {
            $value = $storage->get($key);
            if ($value) {
                break;
            } else {
                $this->emptyStorage[] = $storage;
            }
        }

        return $value;
    }

    /**
     * Method set key->value to all storage.
     *
     * @param string $key   - key value
     * @param mixed  $value - value
     * @param $ttl
     */
    public function setKey(string $key, $value, $ttl): void
    {
        foreach ($this->storage as $storage) {
            $storage->set($key, $value, $ttl);
        }
    }
}

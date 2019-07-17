<?php

namespace App;

class Cache
{
    public $storages;
    public $emptyStorages;

    public function addStorage(object $storage): void
    {
        $this->storages[] = $storage;
    }

    public function set(string $key, $value, $ttl = 3600): void
    {
        $this->setKey($key, $value, $ttl);
    }

    public function get(string $key)
    {
        $value = $this->getKey($key);
        $this->checkEmptyStorages($key, $value);

        return $value;
    }

    public function checkEmptyStorages($key, $value): void
    {
        if ($this->emptyStorages) {
            foreach ($this->emptyStorages as $storage) {
                $storage->set($key, $value);
            }
        }
        $this->emptyStorages = [];
    }

    public function getKey(string $key)
    {
        $value = null;
        foreach ($this->storages as $storage) {
            $value = $storage->get($key);
            if ($value) {
                break;
            } else {
                $this->emptyStorages[] = $storage;
            }
        }

        return $value;
    }

    public function setKey(string $key, $value)
    {
        foreach ($this->storages as $storage) {
            $storage->set($key, $value);
        }
    }
}

<?php

declare(strict_types=1);

interface StorageInterface
{
    /**
     * @param object storage - new layer storage
     */
    public function addStorage(object $newStorage);
}

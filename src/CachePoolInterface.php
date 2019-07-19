<?php

declare(strict_types=1);

namespace App;

interface CachePoolInterface
{
    /**
     * @param CacheInterface $layer
     */
    public function addLayer(CacheInterface $layer);
}

<?php

declare(strict_types=1);

namespace App;

interface CacheLayerInterface extends CacheInterface
{
    /**
     * @param CacheLayerInterface|CacheInterface $layer
     */
    public function addLayer(CacheInterface $layer): void;
}

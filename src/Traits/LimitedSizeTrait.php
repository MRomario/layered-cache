<?php

namespace App\Traits;

trait LimitedSizeTrait
{
    private $limitSize;

    public function setSize(int $size = 0): void
    {
        $this->limitSize = $size;
    }
}

<?php

namespace App\Traits;

trait LimitedSizeTrait
{
    private $limitSize;

    public function setSize(int $size = 5): void
    {
        if ($size) {
            $this->limitSize = $size;
        }
    }
}

<?php

namespace App\Traits;

trait LimitedSizeTrait
{
    private $limitSize = 5;

    public function setSize(int $size = null): void
    {
        if ($size) {
            $this->limitSize = $size;
        }
    }
}

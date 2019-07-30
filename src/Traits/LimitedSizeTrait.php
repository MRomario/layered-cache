<?php

namespace App\Traits;

trait LimitedSizeTrait
{
    private $limitSize = 5;

    public function setSize(int $size): void
    {
        $this->limitSize = $size;
    }
}

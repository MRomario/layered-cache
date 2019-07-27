<?php

namespace App\Traits;

trait LimitedSizeTrait
{
    private $limitSize;

    /**
     * @param int $limitSize
     */
    public function __set(int $limitSize)
    {
        $this->limitSize = $limitSize;
    }
}

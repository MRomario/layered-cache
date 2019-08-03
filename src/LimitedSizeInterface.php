<?php

namespace App;

interface LimitedSizeInterface
{
    /**
     * @param int $size
     */
    public function setSize(int $size): void;
}

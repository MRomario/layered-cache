<?php

namespace App\Traits;

trait LimitedSizeTrait
{
    private $limitSize = 5;

    /**
     * @param $property
     * @param $value
     *
     * @return $this
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }
}

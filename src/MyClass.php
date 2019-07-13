<?php

namespace App;

class MyClass
{
    public function sum(int $one, int $two): int
    {
        if ($one === $two) {
            return $one * $two;
        }

        return $one + $two;
    }
}

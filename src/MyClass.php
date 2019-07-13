<?php

namespace App;

class MyClass
{
    public function sum(int $one, int $two): int
    {
        return ($one === $two) ? $one * $two : $one + $two;
    }
}

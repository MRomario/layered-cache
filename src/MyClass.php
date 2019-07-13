<?php

namespace App;

class MyClass
{
    public function sum(int $one, int $two): int
    {
        if (100 === $one) {
            echo '2';
        }

        return ($one === $two) ? $one * $two : $one + $two;
    }
}

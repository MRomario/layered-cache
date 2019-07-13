<?php

declare(strict_types=1);

namespace App;

class MyClass
{
    /**
     * @param int $one
     * @param int $two
     *
     * @return int
     */
    public function sum(int $one, int $two): int
    {
        if (100 === $one) {
            echo '2';
        }

        return ($one === $two) ? $one * $two : $one + $two;
    }
}

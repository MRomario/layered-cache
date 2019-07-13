<?php

declare(strict_types=1);

namespace App\Tests;

use App\MyClass;
use PHPUnit\Framework\TestCase;

class MyClassTest extends TestCase
{
    public function testSum(): void
    {
        $myClass = new MyClass();
        $this->assertEquals(3, $myClass->sum(1, 2));
    }
}

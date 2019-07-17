<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cache;
use App\CacheStatic;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    public function testSetNewKey(): void
    {
        $class = new Cache();
        $class->addStorage(new CacheStatic());
        $this->assertNull($class->set('name', 'Roman'));
    }

    public function testGetKey()
    {
        $class = new Cache();
        $class->addStorage(new CacheStatic());
        $this->assertNull($class->get('name'));
    }

    public function testCheckEmptyStorages()
    {
        $class = new Cache();
        $class->emptyStorages[] = new CacheStatic();
        $this->assertNull($class->checkEmptyStorages('name', 'roman'));
    }
}

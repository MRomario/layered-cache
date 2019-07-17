<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cache;
use App\CacheStatic;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    /**
     *Test method with injection one storage - set key.
     */
    public function testSetNewKey()
    {
        $class = new Cache();
        $class->addStorage(new CacheStatic());
        $this->assertNull($class->set('name', 'Roman'));
    }

    /**
     *Test method with injection one storage - get key.
     */
    public function testGetKey()
    {
        $class = new Cache();
        $class->addStorage(new CacheStatic());
        $this->assertNull($class->get('name'));
    }

    /**
     *Test method with not empty value - get some value from some storage
     * end rewrite value to empty storage.
     */
    public function testCheckEmptyStorage()
    {
        $class = new Cache();
        $class->emptyStorages[] = new CacheStatic();
        $this->assertNull($class->checkEmptyStorage('name', 'roman'));
    }

    /**
     *Test method with empty value - result search from all storage,
     * end not rewrite value.
     */
    public function testCheckEmptyStorageForeFalseValue()
    {
        $class = new Cache();
        $class->emptyStorages[] = new CacheStatic();
        $this->assertNull($class->checkEmptyStorage('name', false));
    }
}

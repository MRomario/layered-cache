<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cache;
use App\Exception\EmptyKeyException;
use App\Exception\EmptyPoolException;
use App\StaticCache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    private $cache;

    public function setUp(): void
    {
        $this->cache = new Cache();
    }

    public function testGet()
    {
        $cache = new Cache();
        $staticCache = new StaticCache();
        $cache->addLayer($staticCache);

        $secondCache = new Cache();
        $secondStaticCache = new StaticCache();
        $secondCache->addLayer($secondStaticCache);

        $cache->addLayer($secondCache);

        $key = 'test';
        $value = 'test value';
        $cache->set($key, $value);

        $this->assertEquals($value, $cache->get($key));
        $this->assertEquals($value, $staticCache->get($key));
        $this->assertEquals($value, $secondStaticCache->get($key));
    }

    public function testGetEmptyKeyException()
    {
        $this->expectException(EmptyKeyException::class);

        $testKey = ' ';
        $this->cache->get($testKey);
    }

    public function testGetEmptyPoolException()
    {
        $this->expectException(EmptyPoolException::class);

        $testKey = 'test';
        $this->cache->get($testKey);
    }

    public function testGetKeyNotFoundException()
    {
    }
}

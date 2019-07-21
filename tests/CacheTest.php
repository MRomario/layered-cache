<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cache;
use App\Exception\EmptyKeyException;
use App\Exception\EmptyPoolException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;
use App\StaticCache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    private $cache;
    private $staticCache;

    public function setUp(): void
    {
        $this->cache = new Cache();
        $this->staticCache = new StaticCache();
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

    public function testGetEmptyPoolException()
    {
        $this->expectException(EmptyPoolException::class);
        $testKey = 'test';
        $this->cache->get($testKey);
    }

    public function testGetEmptyKeyException()
    {
        $this->expectException(EmptyKeyException::class);
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog');
        $this->cache->get(' ');
    }

    public function testGetKeyNotFoundException()
    {
        $this->expectException(KeyNotFoundException::class);
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog');
        $this->cache->get('not found key');
    }

    public function testGetIssetKeyButOutdated()
    {
        $this->expectException(OutdatedCacheException::class);
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog', -1000);
        $this->cache->get('cat');
    }

    public function testSetKeyValue()
    {
        $this->cache->addLayer($this->staticCache);
        $this->assertNull($this->cache->set('cat', 'dog'));
    }

    public function testAddLayer()
    {
        $this->assertNull($this->cache->addLayer($this->staticCache));
    }

    public function testGetValueEmptyPool()
    {
        $this->expectException(EmptyPoolException::class);
        $this->cache->get('test');
    }
}

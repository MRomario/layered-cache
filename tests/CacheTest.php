<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cache;
use App\Exception\EmptyKeyException;
use App\Exception\EmptyPoolException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;
use App\FileCache;
use App\StaticCache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    private $cache;
    private $staticCache;
    private $fileCache;

    public function setUp(): void
    {
        $this->cache = new Cache();
        $this->staticCache = new StaticCache();
        $this->fileCache = new FileCache();
    }

    public function testGet(): void
    {
        $this->cache->addLayer($this->staticCache);

        $secondCache = new Cache();
        $secondStaticCache = new StaticCache();
        $secondCache->addLayer($secondStaticCache);

        $this->cache->addLayer($secondCache);

        $key = 'test';
        $value = 'test value';
        $this->cache->set($key, $value);

        $this->assertEquals($value, $this->cache->get($key));
        $this->assertEquals($value, $this->staticCache->get($key));
        $this->assertEquals($value, $secondStaticCache->get($key));
    }

    public function testGetEmptyPoolException(): void
    {
        $this->expectException(EmptyPoolException::class);
        $this->cache->get('test');
    }

    public function testGetEmptyKeyException(): void
    {
        $this->expectException(EmptyKeyException::class);
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog');
        $this->cache->get(' ');
    }

    public function testGetKeyNotFoundException(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog');
        $this->cache->get('not found key');
    }

    public function testGetIssetKeyButOutdated(): void
    {
        $this->expectException(OutdatedCacheException::class);
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog', -1000);
        $this->cache->get('cat');
    }

    public function testSetKeyValue(): void
    {
        $this->cache->addLayer($this->staticCache);
        $this->assertNull($this->cache->set('cat', 'dog'));
    }

    public function testAddLayer(): void
    {
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog');
        $this->assertEquals('dog', $this->cache->get('cat'));
    }

    public function testGetValueEmptyPool()
    {
        $this->expectException(EmptyPoolException::class);
        $this->cache->get('test');
    }

    public function testGetValueFromStaticLayerAndFileLayer(): void
    {
        $this->cache->addLayer($this->staticCache);
        $this->cache->addLayer($this->fileCache);

        $this->cache->set('cat', 'cat');
        $this->cache->set('dog', 'dog');
        $this->cache->set('car', 'car');

        $this->assertEquals('cat', $this->fileCache->get('cat'));
        $this->assertEquals('cat', $this->staticCache->get('cat'));
        $this->assertEquals('cat', $this->cache->get('cat'));
    }

    public function testLimitCacheFromAllLayers(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->cache->addLayer($this->staticCache);
        $this->cache->addLayer($this->fileCache);

        $this->cache->set('1', 1, -1000);
        $this->cache->set('2', 2);
        $this->cache->set('3', 3);
        $this->cache->set('4', 4);
        $this->cache->set('5', 5);
        $this->cache->set('6', 6);

        $this->assertEquals(6, $this->cache->get('6'));
        $this->assertEquals(1, $this->cache->get('1'));
    }

    public function testClearCacheFromAllLayers(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->cache->addLayer($this->staticCache);
        $this->cache->addLayer($this->fileCache);

        $this->cache->set('cat', 'cat');
        $this->cache->set('dog', 'dog');
        $this->cache->set('car', 'car');

        $this->cache->clear();

        $this->assertEquals('dog', $this->cache->get('dog'));
    }

    public function testSetLimitSizeCache(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->staticCache->setSize(2);

        $this->cache->addLayer($this->staticCache);

        $this->cache->set('1', 1, -1000);
        $this->cache->set('2', 2);
        $this->cache->set('3', 3);

        $this->assertEquals(1, $this->staticCache->get('1'));
        $this->cache->clear();
    }
}

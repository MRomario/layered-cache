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

    public function testGet()
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

    public function testGetEmptyPoolException()
    {
        $this->expectException(EmptyPoolException::class);
        $this->cache->get('test');
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
        $this->cache->addLayer($this->staticCache);
        $this->cache->set('cat', 'dog');
        $this->assertEquals('dog', $this->cache->get('cat'));
    }

    public function testGetValueEmptyPool()
    {
        $this->expectException(EmptyPoolException::class);
        $this->cache->get('test');
    }

    public function testGetValueFromStaticLayerAndFileLayer()
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

    public function testClearCacheFromAllLayers()
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
}

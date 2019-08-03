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

    public function tearDown(): void
    {
        parent::tearDown();
        $this->cache->clear();
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
        $this->assertEquals(1, $this->cache->get('1'));
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

        for ($i = 1; $i <= 3; ++$i) {
            $this->cache->set('1', 1);
        }

        $this->assertEquals(1, $this->fileCache->get('1'));
        $this->assertEquals(1, $this->staticCache->get('1'));
        $this->assertEquals(1, $this->cache->get('1'));
    }

    public function testLimitCacheFromAllLayers(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->fileCache->setSize(2);
        $this->staticCache->setSize(2);

        $this->cache->addLayer($this->staticCache);
        $this->cache->addLayer($this->fileCache);

        for ($i = 1; $i <= 3; ++$i) {
            if (1 === $i) {
                $this->cache->set('1', 1, -3600);
            }
            $this->cache->set("$i", $i);
        }

        $this->assertEquals(1, $this->cache->get('1'));
    }

    public function testNoLimitCacheFromAllLayers(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->cache->addLayer($this->staticCache);
        $this->cache->addLayer($this->fileCache);

        for ($i = 1; $i <= 5; ++$i) {
            if (1 === $i) {
                $this->cache->set('1', 1, -3600);
            }
            $this->cache->set("$i", $i);
        }

        for ($i = 1; $i <= 6; ++$i) {
            $this->assertEquals($i, $this->cache->get("$i"));
        }
    }

    public function testClearCacheFromAllLayers(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->cache->addLayer($this->staticCache);
        $this->cache->addLayer($this->fileCache);

        for ($i = 1; $i <= 6; ++$i) {
            $this->cache->set("$i", $i);
        }
        $this->cache->clear();
        $this->assertEquals(1, $this->cache->get('1'));
    }

    public function testConstructorLimitCacheSizeLayers(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->cache->addLayer(new StaticCache(2));
        $this->cache->addLayer(new FileCache(2));

        for ($i = 1; $i <= 3; ++$i) {
            if (1 === $i) {
                $this->cache->set('1', 1, -1000);
            }
            $this->cache->set("$i", $i);
        }
        $this->assertEquals(1, $this->cache->get('1'));
    }
}

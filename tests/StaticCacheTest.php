<?php

declare(strict_types=1);

namespace App\Tests;

use App\Exception\EmptyKeyException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;
use App\StaticCache;
use PHPUnit\Framework\TestCase;

class StaticCacheTest extends TestCase
{
    private $staticCache;
    private $testKey = '1';
    private $testValue = 1;

    public function setUp(): void
    {
        parent::setUp();
        $this->staticCache = new StaticCache();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->staticCache->clear();
    }

    public function testSetValue(): void
    {
        $this->staticCache->set($this->testKey, $this->testValue);
        $this->assertEquals($this->testValue, $this->staticCache->get($this->testKey));
    }

    public function testKeyNotFound(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->staticCache->get('error');
    }

    public function testSetEmptyKey(): void
    {
        $this->expectException(EmptyKeyException::class);
        $this->testKey = ' ';
        $this->staticCache->set($this->testKey, $this->testValue);
    }

    public function testGetEmptyKey(): void
    {
        $this->expectException(EmptyKeyException::class);
        $this->testKey = ' ';
        $this->assertEquals($this->testKey, $this->staticCache->get($this->testKey, $this->testValue));
    }

    public function testOutdatedTtl(): void
    {
        $this->expectException(OutdatedCacheException::class);
        $ttl = -1111;
        $this->staticCache->set($this->testKey, $this->testValue, $ttl);
        $this->staticCache->get($this->testKey);
    }

    public function testLimitCacheSix(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->staticCache->setSize(5);

        for ($i = 1; $i <= 6; ++$i) {
            if (1 === $i) {
                $this->staticCache->set('1', 1, -1000);
            }
            $this->staticCache->set("$i", $i);
        }
        $this->assertEquals(1, $this->staticCache->get('1'));
    }

    public function testClearCache(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->staticCache->set('1', 1);
        $this->staticCache->clear('1');
        $this->staticCache->get('1');
    }

    public function testSetLimitSizeCache(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->staticCache->setSize(2);

        for ($i = 1; $i <= 3; ++$i) {
            if (1 === $i) {
                $this->staticCache->set('1', 1, -1000);
            }
            $this->staticCache->set("$i", $i);
        }
        $this->assertEquals(1, $this->staticCache->get('1'));
    }

    public function testConstructorLimitCacheSize(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $staticCache = new StaticCache(2);
        for ($i = 1; $i <= 3; ++$i) {
            if (1 === $i) {
                $staticCache->set('1', 1, -1000);
            }
            $staticCache->set("$i", $i);
        }
        $this->assertEquals(1, $staticCache->get('1'));
    }
}

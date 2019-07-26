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
    private $testValue = 'test value';
    private $testKey = 'test';

    public function setUp(): void
    {
        $this->staticCache = new StaticCache();
    }

    public function testSetValue(): void
    {
        $this->staticCache->set($this->testKey, $this->testValue);
        $this->assertEquals($this->testValue, $this->staticCache->get($this->testKey));
    }

    public function testOutdatedTtl(): void
    {
        $this->expectException(OutdatedCacheException::class);
        $ttl = -1111;

        $this->staticCache->set($this->testKey, $this->testValue, $ttl);
        $this->staticCache->get($this->testKey);
    }

    public function testKeyNotFound(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $testKey = 'test';

        $this->staticCache->set($testKey, $this->testValue);
        $this->staticCache->get('error');
    }

    public function testSetEmptyKey(): void
    {
        $this->expectException(EmptyKeyException::class);

        $testKey = ' ';
        $this->staticCache->set($testKey, $this->testValue);
    }

    public function testGetEmptyKey(): void
    {
        $this->expectException(EmptyKeyException::class);

        $testKey = ' ';

        $this->staticCache->set($testKey, $this->testValue);
    }

    public function testClearCache()
    {
        $this->expectException(KeyNotFoundException::class);
        $this->staticCache->set('cat', 'cat');
        $this->staticCache->clear('cat');
        $this->staticCache->get('cat');
    }

    public function testLimitCacheFive()
    {
        // limit 5 keys
        $this->staticCache->set('1', 1, 100);
        $this->staticCache->set('2', 2, 200);
        $this->staticCache->set('3', 3, 300);
        $this->staticCache->set('4', 4, 400);
        $this->staticCache->set('5', 5, 500);

        $this->assertEquals(1, $this->staticCache->get('1'));
    }

    public function testLimitCacheSix()
    {
        $this->expectException(KeyNotFoundException::class);
        // limit 5 keys
        $this->staticCache->set('1', 1, 100);
        $this->staticCache->set('2', 2, 200);
        $this->staticCache->set('3', 3, 300);
        $this->staticCache->set('4', 4, 400);
        $this->staticCache->set('5', 5, 500);
        $this->staticCache->set('6', 6, 600);

        $this->assertEquals(1, $this->staticCache->get('1'));
    }
}

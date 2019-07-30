<?php

declare(strict_types=1);

namespace App\Tests;

use App\Exception\EmptyKeyException;
use App\Exception\KeyNotFoundException;
use App\Exception\OutdatedCacheException;
use App\FileCache;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    public $fileCache;

    public function setUp(): void
    {
        parent::setUp();
        $this->fileCache = new FileCache();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->fileCache->clear();
    }

    public function testGetEmptyKey(): void
    {
        $this->expectException(EmptyKeyException::class);
        $this->fileCache->get('');
        $this->fileCache->get(' ');
    }

    public function testGetNotExistingKey(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->fileCache->get('not existing key');
    }

    public function testSetNewKey(): void
    {
        $this->fileCache->set('1', 1);
        $this->assertEquals(1, $this->fileCache->get('1'));
    }

    public function testGetExistingKey(): void
    {
        $this->fileCache->set('1', 1);
        $this->assertEquals(1, $this->fileCache->get('1'));
    }

    public function testGetOutdatedKey(): void
    {
        $this->expectException(OutdatedCacheException::class);
        $this->fileCache->set('1', 1, -1000);
        $this->fileCache->get('1');
    }

    public function testLimitKeyCacheTrue(): void
    {
        $this->fileCache->setSize(4);
        for ($i = 1; $i <= 4; ++$i) {
            $this->fileCache->set("$i", $i);
        }
        $this->assertEquals(4, $this->fileCache->get('4'));
    }

    public function testLimitKeyCacheFalse(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->fileCache->setSize(2);
        for ($i = 1; $i <= 3; ++$i) {
            if (1 === $i) {
                $this->fileCache->set('1', 1, -3600);
            }
            $this->fileCache->set("$i", $i);
        }
        $this->assertEquals(1, $this->fileCache->get('1'));
    }

    public function testClearCache(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->fileCache->set('1', 1);
        $this->fileCache->clear();
        $this->assertEquals(2, $this->fileCache->get('2'));
    }
}

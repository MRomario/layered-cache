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
        $this->fileCache = new FileCache();
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
        $this->fileCache->clear();
    }

    public function testSetNewKey(): void
    {
        $this->fileCache->set('cat', 'dog');
        $this->assertEquals('dog', $this->fileCache->get('cat'));
        $this->fileCache->clear();
    }

    public function testGetExistingKey(): void
    {
        $this->fileCache->set('cat', 'dog');
        $this->assertEquals('dog', $this->fileCache->get('cat'));
        $this->fileCache->clear();
    }

    public function testLimitKeyCacheFour(): void
    {
        // limit 5 - 4 keys
        $this->fileCache->set('1', 1);
        $this->fileCache->set('2', 2);
        $this->fileCache->set('3', 3);
        $this->fileCache->set('4', 4);
        $this->fileCache->set('5', 5);

        $this->assertEquals(5, $this->fileCache->get('5'));
        $this->fileCache->clear();
    }

    public function testLimitKeyCacheFive(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->fileCache->set('1', 1, -1000);
        $this->fileCache->set('2', 2);
        $this->fileCache->set('3', 3);
        $this->fileCache->set('4', 4);
        $this->fileCache->set('5', 5);
        $this->fileCache->set('6', 6);

        $this->assertEquals(1, $this->fileCache->get('1'));
        $this->fileCache->clear();
    }

    public function testGetOutdatedKey(): void
    {
        $this->expectException(OutdatedCacheException::class);
        $this->fileCache->set('cat', 'dog', -1000);
        $this->fileCache->get('cat');
        $this->fileCache->clear();
    }

    public function testClearCache(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->fileCache->set('cat', 'cat');
        $this->fileCache->clear();

        $this->assertEquals('cat', $this->fileCache->get('cat'));
        $this->fileCache->clear();
    }

    public function testSetLimitSizeCache(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $this->fileCache->setSize(2);

        $this->fileCache->set('1', 1, -1000);
        $this->fileCache->set('2', 2);
        $this->fileCache->set('3', 3);

        $this->assertEquals(1, $this->fileCache->get('1'));
        $this->fileCache->clear();
    }
}

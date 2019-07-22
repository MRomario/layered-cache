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

    public function testCreateObject()
    {
        try {
            $fileCache = new FileCache();
        } catch (InvalidArgumentException $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    public function testGetEmptyKey()
    {
        $this->expectException(EmptyKeyException::class);
        $this->fileCache->get('');
        $this->fileCache->get(' ');
    }

    public function testGetNotExistingKey()
    {
        $this->expectException(KeyNotFoundException::class);
        $this->fileCache->get('not existing key');
    }

    public function testSetNewKey()
    {
        try {
            $this->fileCache->set('cat', 'dog');
            $this->fileCache->set('color', 'green');
            $this->fileCache->set('test', 'test');
        } catch (InvalidArgumentException $e) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    public function testGetExistingKey()
    {
        $this->assertEquals('dog', $this->fileCache->get('cat'));
    }

    public function testGetOutdatedKey()
    {
        $this->expectException(OutdatedCacheException::class);
        $this->fileCache->set('cat', 'dog', -1111);
        $this->fileCache->get('cat');
    }

    public function testDeleteAllKeyCacheFiles()
    {
        try {
            $this->fileCache->deleteAllKeysCache();
        } catch (InvalidArgumentException $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }
}

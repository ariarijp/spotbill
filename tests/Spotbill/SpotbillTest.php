<?php

namespace Spotbill\Tests;

class SpotbillTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefaultInodeNumberChangedHandler()
    {
        \Closure::bind(function () {
            $handler = \Spotbill\Spotbill::getDefaultInodeNumberChangedHandler();
            $this->assertInternalType('callable', $handler);
        }, $this, '\Spotbill\Spotbill')->__invoke();
    }

    public function testSetMaxRetryCount()
    {
        $this->assertEquals(-1, \Spotbill\Spotbill::getMaxRetryCount());
        \Spotbill\Spotbill::setMaxRetryCount(10);
        $this->assertEquals(10, \Spotbill\Spotbill::getMaxRetryCount());
        \Spotbill\Spotbill::setMaxRetryCount();
        $this->assertEquals(-1, \Spotbill\Spotbill::getMaxRetryCount());
    }

    public function testSetSleepSeconds()
    {
        $this->assertEquals(1, \Spotbill\Spotbill::getSleepSeconds());
        \Spotbill\Spotbill::setSleepSeconds(10);
        $this->assertEquals(10, \Spotbill\Spotbill::getSleepSeconds());
        \Spotbill\Spotbill::setSleepSeconds();
        $this->assertEquals(1, \Spotbill\Spotbill::getSleepSeconds());
    }

    public function testGetFilePointer()
    {
        \Closure::bind(function () {
            $handler = \Spotbill\Spotbill::getDefaultInodeNumberChangedHandler();

            $fileName1 = __DIR__.'/../tmp/file1';
            $fp1 = fopen($fileName1, 'w');
            $fp1 = \Spotbill\Spotbill::getFilePointer($fp1, $fileName1, $handler);
            $this->assertInternalType('resource', $fp1);
            fclose($fp1);

            $fileName2 = __DIR__.'/../tmp/file2';
            $fp2 = fopen($fileName2, 'w');
            fclose($fp2);

            rename($fileName2, $fileName1);
            $fp1 = fopen($fileName1, 'w');
            $fp1 = \Spotbill\Spotbill::getFilePointer($fp1, $fileName1, $handler);
            $this->assertInternalType('resource', $fp1);
            fclose($fp1);

            unlink($fileName1);
        }, $this, '\Spotbill\Spotbill')->__invoke();
    }
}

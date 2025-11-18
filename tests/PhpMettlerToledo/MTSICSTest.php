<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\Exception\CommandException;
use PhpMettlerToledo\SICS\ExecuteCommandInterface;
use PHPUnit\Framework\TestCase;

class MTSICSTest extends TestCase
{
    private function createMockExecuteCommand(): ExecuteCommandInterface
    {
        return $this->createMock(ExecuteCommandInterface::class);
    }

    public function testReadCommandsAvailableReturnsArray()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readCommandsAvailable')
            ->willReturn(['Z', 'ZI', 'S', 'SI', 'T', 'TI']);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readCommandsAvailable();

        $this->assertIsArray($result);
        $this->assertCount(6, $result);
        $this->assertContains('Z', $result);
    }

    public function testReadCommandsAvailableReturnsErrorOnException()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readCommandsAvailable')
            ->willThrowException(new CommandException('Test error'));

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readCommandsAvailable();

        $this->assertIsArray($result);
        $this->assertEquals(['Test error'], $result);
        $this->assertEquals('Test error', $mtsics->getError());
    }

    public function testReadWeightAndStatusReturnsFloat()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readWeightAndStatus')
            ->willReturn(123.45);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readWeightAndStatus();

        $this->assertIsFloat($result);
        $this->assertEquals(123.45, $result);
    }

    public function testReadWeightAndStatusReturnsZeroOnError()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readWeightAndStatus')
            ->willThrowException(new CommandException('Balance not ready'));

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readWeightAndStatus();

        $this->assertIsFloat($result);
        $this->assertEquals(0, $result);
        $this->assertEquals('Balance not ready', $mtsics->getError());
    }

    public function testReadTareWeightReturnsFloat()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readTareWeight')
            ->willReturn(10.5);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readTareWeight();

        $this->assertIsFloat($result);
        $this->assertEquals(10.5, $result);
    }

    public function testReadNetWeightReturnsFloat()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readNetWeight')
            ->willReturn(112.95);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readNetWeight();

        $this->assertIsFloat($result);
        $this->assertEquals(112.95, $result);
    }

    public function testZeroStableReturnsTrue()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('zeroStable')
            ->willReturn(null);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->zeroStable();

        $this->assertTrue($result);
    }

    public function testZeroStableReturnsFalseOnError()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('zeroStable')
            ->willThrowException(new CommandException('Cannot zero'));

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->zeroStable();

        $this->assertFalse($result);
        $this->assertEquals('Cannot zero', $mtsics->getError());
    }

    public function testZeroImmediatelyReturnsTrue()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('zeroImmediately')
            ->willReturn(null);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->zeroImmediately();

        $this->assertTrue($result);
    }

    public function testTareStableReturnsTrue()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('tareStable')
            ->willReturn(null);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->tareStable();

        $this->assertTrue($result);
    }

    public function testTareImmediatelyReturnsTrue()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('tareImmediately')
            ->willReturn(null);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->tareImmediately();

        $this->assertTrue($result);
    }

    public function testClearTareReturnsTrue()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('clearTare')
            ->willReturn(null);

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->clearTare();

        $this->assertTrue($result);
    }

    public function testReadFirmwareRevisionReturnsString()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readFirmwareRevision')
            ->willReturn('1.2.3');

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readFirmwareRevision();

        $this->assertIsString($result);
        $this->assertEquals('1.2.3', $result);
    }

    public function testReadFirmwareRevisionReturnsEmptyStringOnError()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readFirmwareRevision')
            ->willThrowException(new CommandException('Cannot read firmware'));

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readFirmwareRevision();

        $this->assertIsString($result);
        $this->assertEquals('', $result);
        $this->assertEquals('Cannot read firmware', $mtsics->getError());
    }

    public function testReadSerialNumberReturnsString()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readSerialNumber')
            ->willReturn('SN123456');

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readSerialNumber();

        $this->assertIsString($result);
        $this->assertEquals('SN123456', $result);
    }

    public function testReadSerialNumberReturnsEmptyStringOnError()
    {
        $mock = $this->createMockExecuteCommand();
        $mock->method('readSerialNumber')
            ->willThrowException(new CommandException('Cannot read serial'));

        $mtsics = new MTSICS('127.0.0.1', 4305, $mock);
        $result = $mtsics->readSerialNumber();

        $this->assertIsString($result);
        $this->assertEquals('', $result);
        $this->assertEquals('Cannot read serial', $mtsics->getError());
    }
}

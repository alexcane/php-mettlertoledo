<?php

namespace PhpMettlerToledo;

use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    const IP_ADDRESS = '172.20.2.80';
    const PORT = 4305;

    public function testReadCommandsAvailableIsArray()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->readCommandsAvailable();
        $this->assertIsArray($result);
    }

    public function testReadWeightAndStatusIsFloat()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->readWeightAndStatus();
        $this->assertIsFloat($result);
    }

    public function testReadTareWeightIsFloat()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->readTareWeight();
        $this->assertIsFloat($result);
    }

    public function testReadNetWeightIsFloat()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->readNetWeight();
        $this->assertIsFloat($result);
    }

    public function testZeroStableIsTrue()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->zeroStable();
        $this->assertTrue($result);
    }

    public function testZeroImmediatelyIsTrue()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->zeroImmediately();
        $this->assertTrue($result);
    }

    public function testTareStableIsTrue()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->tareStable();
        $this->assertTrue($result);
    }

    public function testTareImmediatelyIsTrue()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->tareImmediately();
        $this->assertTrue($result);
    }

    public function testClearTareIsTrue()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->clearTare();
        $this->assertTrue($result);
    }

    public function testReadFirmwareRevisionIsString()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->readFirmwareRevision();
        $this->assertIsString($result);
    }

    public function testReadSerialNumberIsString()
    {
        $MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
        $result = $MTSICS->readSerialNumber();
        $this->assertIsString($result);
    }
}
<?php

namespace PhpMettlerToledo;

use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    const IP_ADDRESS = '172.20.2.81';
    const PORT = 4305;
    private MTSICS $_MTSICS;

    function __construct()
    {
        parent::__construct();
        $this->_MTSICS = new MTSICS(self::IP_ADDRESS, self::PORT);
    }
    public function testReadNetWeightIsFloat()
    {
        $result = $this->_MTSICS->readNetWeight();
        $this->assertIsFloat($result);
    }
}
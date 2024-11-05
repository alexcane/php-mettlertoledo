<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\Exception\ConnectionException;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnectionWithBadHost()
    {
        $badHost = 'bad_host';
        $this->expectException(ConnectionException::class);
        $this->expectExceptionMessage('Host invalid: ' . $badHost);
        new Connection($badHost);
    }

    /**
     * @throws ConnectionException
     */
    public function testGoodHost()
    {
        $conn = new Connection('172.20.2.80', 4305);
        $this->assertTrue($conn->isConnected());
    }
}

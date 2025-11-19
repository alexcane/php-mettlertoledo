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

    public function testConnectionWithUnreachableHost()
    {
        $this->expectException(ConnectionException::class);
        // Using localhost with unlikely port to test connection failure
        new Connection('127.0.0.1', 59999);
    }
}

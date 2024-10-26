<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\Exception\ConnectionException;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testBadHost()
    {
        $this->expectException(ConnectionException::class);
        $this->expectExceptionMessage('Server bad.host is down or does not exist');
        new Connection('bad.host');
    }
}

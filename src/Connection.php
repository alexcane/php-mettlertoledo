<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\Exception\ConnectionException;

class Connection
{
    const STATE_INIT = 'init';
    const STATE_READY = 'ready';
    const STATE_ERROR = 'error';
    const STATE_BUSY = 'busy';
    private string $_host;
    private int $_port;
    private string $_state = self::STATE_INIT;
    private $_stream;
    protected string $_connectionError;

    public function __construct(string $host, int $port=4305)
    {
        $this->_host = $host;
        $this->_port = $port;
        try {
            $this->checkConnection();
            if ($this->isConnected()) throw new ConnectionException('Scale not connected');
            $this->_state = self::STATE_READY;
        } catch (ConnectionException $e) {
            error_log($e->getMessage());
            $this->_connectionError = $e->getMessage();
            $this->_state = self::STATE_ERROR;
        }
    }
    
    public function getState(): string
    {
        return $this->_state;
    }

    public function setStateInit()
    {
        $this->_state = self::STATE_INIT;
    }
    public function setStateReady()
    {
        $this->_state = self::STATE_READY ;
    }
    public function setStateError()
    {
        $this->_state = self::STATE_ERROR ;
    }
    public function setStateBusy()
    {
        $this->_state = self::STATE_BUSY;
    }

    public function getSream()
    {
        return $this->_stream;
    }

    public function isConnected(): bool
    {
        return is_resource($this->_stream);
    }

    private function checkHost(): void
    {
        if (!filter_var($this->_host, FILTER_VALIDATE_IP) && gethostbyname($this->_host) === $this->_host) {
            throw new ConnectionException('Server '. $this->_host . ' is down or does not exist');
        }
    }

    private function checkConnection(): void
    {
        $this->checkHost();
        error_reporting(0);
        $socketConnection = fsockopen($this->_host, $this->_port, $errCode, $errMsg);
        if(!$socketConnection){
            $errMsg = mb_convert_encoding(rtrim($errMsg), 'UTF-8', 'ISO-8859-1') .' ('. $errCode .')';
            throw new ConnectionException("Connection failed: $errMsg");
        }
        $this->_stream = $socketConnection;
    }
}
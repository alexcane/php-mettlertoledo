<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\SICS\ExecuteCommand;

class MTSICS extends Connection
{
    private ExecuteCommand $_exec;
    private string $_error;

    function __constructor(string $host, int $port)
    {
        parent::__construct($host, $port);
        if($this->getState() === Connection::STATE_ERROR) $this->_error = $this->_connectionError;
        $this->_exec = new ExecuteCommand($this);
    }

    /**
     * @return float
     */
    public function readWeightAndStatus(): float
    {
        try {
            return $this->_exec->readWeightAndStatus();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return float
     */
    public function readTareWeight(): float
    {
        try {
            return $this->_exec->readTareWeight();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }


    /**
     * @return float
     */
    public function readNetWeight(): float
    {
        try {
            return $this->_exec->readNetWeight();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function zeroStable(): bool
    {
        try {
            return $this->_exec->zeroStable();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function zeroImmediately(): bool
    {
        try {
            return $this->_exec->zeroImmediately();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function tareStable(): bool
    {
        try {
            return $this->_exec->tareStable();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function tareImmediatly(): bool
    {
        try {
            return $this->_exec->tareImmediatly();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function clearTare(): bool
    {
        try {
            return $this->_exec->clearTare();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return string
     */
    public function readFirmwareRevision(): string
    {
        try {
            return $this->_exec->readFirmwareRevision();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return string
     */
    public function readSerialNumber(): string
    {
        try {
            return $this->_exec->readSerialNumber();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->_error;
    }
}
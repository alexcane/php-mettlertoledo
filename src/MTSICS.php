<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\Exception\ConnectionException;
use PhpMettlerToledo\SICS\ExecuteCommand;

class MTSICS extends Connection
{
    private ?ExecuteCommand $_exec = null;
    private string $_error = '';

    function __construct(string $host, int $port)
    {
        try {
            parent::__construct($host, $port);
        } catch (ConnectionException $e) {
            $this->_error = $e->getMessage();
            $this->setStateError();
        }
        if($this->getState() === Connection::STATE_READY){
            $this->_exec = new ExecuteCommand($this);
        }
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
            return 0;
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
            return 0;
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
            return 0;
        }
    }

    /**
     * @return bool
     */
    public function zeroStable(): bool
    {
        try {
            $this->_exec->zeroStable();
            return true;
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
            $this->_exec->zeroImmediately();
            return true;
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
            $this->_exec->tareStable();
            return true;
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
            $this->_exec->tareImmediatly();
            return true;
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
            $this->_exec->clearTare();
            return true;
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
            return '';
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
            return '';
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
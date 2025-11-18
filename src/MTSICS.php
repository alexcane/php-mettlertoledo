<?php

namespace PhpMettlerToledo;

use PhpMettlerToledo\Exception\ConnectionException;
use PhpMettlerToledo\SICS\ExecuteCommand;

/**
 * Class MTSICS
 * Provides an interface to communicate with Mettler Toledo scales using SICS (Standard Interface Command Set).
 * Handles connection and execution of scale commands, including weight and status retrieval, tare management, and error handling.
 */
class MTSICS extends Connection
{
    private ?ExecuteCommand $_exec = null;
    private string $_error = '';

    /**
     * Constructor
     * Initializes a connection to the Mettler Toledo scale.
     * Sets an error state if connection fails.
     * @param string $host The IP address of the Mettler Toledo scale.
     * @param int $port The port number used for the connection.
     */
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
     * Retrieves a list of available commands supported by the Mettler Toledo scale.
     * @return array A list of available commands as an array. If an error occurs, returns an array with the error message.
     */
    public function readCommandsAvailable(): array
    {
        try {
            return $this->_exec->readCommandsAvailable();
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return [$this->_error];
        }
    }

    /**
     * Reads the current weight and status from the scale.
     * @return float The current weight reading in the appropriate unit. Returns 0 in case of an error.
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
     * Reads the tare weight from the scale.
     * @return float The tare weight value. Returns 0 in case of an error.
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
     * Reads the net weight from the scale, irrespective of balance stability.
     * @return float The net weight value. Returns 0 in case of an error.
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
     * Zeros the scale using a stable zero method.
     * @return bool True if the operation is successful, false if an error occurs.
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
     * Zeros the scale immediately regardless the stability of the balance.
     * @return bool True if the operation is successful, false if an error occurs.
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
     * Sets the tare on the scale using a stable method.
     * @return bool True if the operation is successful, false if an error occurs.
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
     * Sets the tare on the scale immediately regardless the stability of the balance.
     * @return bool True if the operation is successful, false if an error occurs.
     */
    public function tareImmediately(): bool
    {
        try {
            $this->_exec->tareImmediately();
            return true;
        } catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    /**
     * Clears the current tare value on the scale.
     * @return bool True if the operation is successful, false if an error occurs.
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
     * Returns the balance SW version and type definition number.
     * @return string The firmware revision as a string. Returns an empty string in case of an error.
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
     * Returns the serial number of the scale.
     * @return string The serial number as a string. Returns an empty string in case of an error.
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
     * Returns the last error message encountered.
     * @return string The last error message as a string.
     */
    public function getError(): string
    {
        return $this->_error;
    }
}
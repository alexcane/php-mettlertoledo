<?php

namespace PhpMettlerToledo\SICS;

use PhpMettlerToledo\Connection;
use PhpMettlerToledo\Exception\CommandException;

class ExecuteCommand
{
    private $_connection;
    function __construct(Connection $connection)
    {
        $this->_connection = $connection;
    }

    public function readWeightAndStatus(): float
    {
        fputs($this->_connection->getSream(), Commands::READ_WEIGHT_AND_STATUS . Commands::CR_LF);
    }

    public function readTareWeight(): float
    {
        fputs($this->_connection->getSream(), Commands::READ_TARE_WEIGHT . Commands::CR_LF);
    }

    /**
     * @throws CommandException
     */
    public function readNetWeight(): float
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();

        fputs($stream, Commands::READ_NET_WEIGHT . Commands::CR_LF);
        $result = fread($stream,17);

        if (!$result || $result===Commands::READ_NET_WEIGHT) throw new CommandException('Execution error : read net weight');
        $this->checkErrorResponse($result);

        //TODO: clean the string with method unpackMessageDetails()
        $result = floatval(trim(str_replace(['S S','S D','kg'], '', $result)));
        fclose($stream);

        if(0>$result) throw new CommandException('Negative weight, check the tare');
        if(0.0===$result) throw new CommandException('Scale seems empty');
        return $result;
    }

    public function zeroStable(): bool
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::ZERO_STABLE_COMMAND . Commands::CR_LF);
    }

    public function zeroImmediately(): bool
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::ZERO_IMMEDIATELY_COMMAND . Commands::CR_LF);
    }

    public function tareStable(): bool
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::TARE_STABLE_COMMAND . Commands::CR_LF);
    }

    public function tareImmediatly(): bool
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::TARE_IMMEDIATELY_COMMAND . Commands::CR_LF);
    }

    public function clearTare(): bool
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::CLEAR_TARE_COMMAND . Commands::CR_LF);
    }

    public function readFirmwareRevision(): string
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_FIRMWARE_REVISION . Commands::CR_LF);
    }

    public function readSerialNumber(): string
    {
        $this->_connection->setStateBusy();
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_SERIAL_NUMBER . Commands::CR_LF);
    }

    /**
     * @throws CommandException
     */
    private function checkErrorResponse(string $response)
    {
        $err = substr($response, 0, 2);
        switch ($err){
            case 'ES':
                throw new CommandException('Syntax error!');
            case 'ET':
                throw new CommandException('Transmission error!');
            case 'EL':
                throw new CommandException('Logical error!');
        }
    }
}
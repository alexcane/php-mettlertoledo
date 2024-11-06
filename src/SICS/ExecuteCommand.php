<?php

namespace PhpMettlerToledo\SICS;

use PhpMettlerToledo\Connection;
use PhpMettlerToledo\Exception\CommandException;
use ReflectionClass;

class ExecuteCommand
{
    private Connection $_connection;
    function __construct(Connection $connection)
    {
        $this->_connection = $connection;
    }

    /**
     * @throws CommandException
     */
    public function readCommandsAvailable(): array
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_COMMANDS_AVAILABLE . Commands::CR_LF);
        $result = [];
        for($i = 0; $i < 100; $i++){
            $line = stream_get_line($stream, 1024, Commands::CR_LF);
            if($line === false) break;
            $line = trim(str_replace(['I0 A','I0 B'], '', $line));
            if(!empty($line)) $result[] = $line;
        }
        fclose($stream);
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function readWeightAndStatus(): float
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_WEIGHT_AND_STATUS . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : read weight and status');
        $this->checkErrorResponse($result);
        $result = floatval(trim(str_replace(['SIX1'], '', $result)));
        fclose($stream);
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function readTareWeight(): float
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_TARE_WEIGHT . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : tare weight');
        $this->checkErrorResponse($result);
        $result = floatval(trim(str_replace(['TA'], '', $result)));
        fclose($stream);
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function readNetWeight(): float
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_NET_WEIGHT . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : read net weight');
        $this->checkErrorResponse($result);
        $result = floatval(trim(str_replace(['S S','S D','kg','g'], '', $result)));
        fclose($stream);
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function zeroStable(): void
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::ZERO_STABLE_COMMAND . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : zero stable');
        $this->checkErrorResponse($result);
    }

    /**
     * @throws CommandException
     */
    public function zeroImmediately(): void
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::ZERO_IMMEDIATELY_COMMAND . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : tare immediatly');
        $this->checkErrorResponse($result);
    }

    /**
     * @throws CommandException
     */
    public function tareStable(): void
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::TARE_STABLE_COMMAND . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : tare stable');
        $this->checkErrorResponse($result);
    }

    /**
     * @throws CommandException
     */
    public function tareImmediatly(): void
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::TARE_IMMEDIATELY_COMMAND . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : tare immediately');
        $this->checkErrorResponse($result);
    }

    /**
     * @throws CommandException
     */
    public function clearTare(): void
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::CLEAR_TARE_COMMAND . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : clear tare');
        $this->checkErrorResponse($result);
    }

    /**
     * @throws CommandException
     */
    public function readFirmwareRevision(): string
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_FIRMWARE_REVISION . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : read firmware revision');
        $this->checkErrorResponse($result);
        $result = trim(str_replace(['I3 A', '"'], '', $result));
        fclose($stream);
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function readSerialNumber(): string
    {
        $stream = $this->_connection->getSream();
        fputs($stream, Commands::READ_SERIAL_NUMBER . Commands::CR_LF);
        $result = stream_get_line($stream,1024, Commands::CR_LF);
        if (!$result) throw new CommandException('Execution error : read serial number');
        $this->checkErrorResponse($result);
        $result = trim(str_replace(['I4 A', '"'], '', $result));
        fclose($stream);
        return $result;
    }

    /**
     * @throws CommandException
     */
    private function checkErrorResponse(string $response)
    {
        $err = substr($response, 0, 2);
        switch ($err){
            case 'ES':
                throw new CommandException('Syntax error');
            case 'ET':
                throw new CommandException('Transmission error');
            case 'EL':
                throw new CommandException('Logical error');
        }

        $commandList = (new ReflectionClass(Commands::class))->getConstants();
        unset($commandList['CR_LF']);
        $commandList = join('|', $commandList);

        $failureMessageRegex = '^(' .$commandList .') [I]';
        if(preg_match($failureMessageRegex, $response)){
            throw new CommandException('Command understood but currently not executable');
        }

        $invalidMessageRegex = '^(' .$commandList .') [L]';
        if(preg_match($invalidMessageRegex, $response)){
            throw new CommandException('Command understood but not executable (incorrect parameter)');
        }

        $underloadMessageRegex = '^(' .$commandList .') [\u002D\\-]';
        if(preg_match($underloadMessageRegex, $response)){
            throw new CommandException('Balance in underload range');
        }

        $overloadMessageRegex = '^(' .$commandList .') [\u002B\\+]';
        if(preg_match($overloadMessageRegex, $response)){
            throw new CommandException('Balance in overload range');
        }

//        $SuccessMessageRegex = "^(Z|ZI|T|TI|TAC) [ADS]";
    }
}
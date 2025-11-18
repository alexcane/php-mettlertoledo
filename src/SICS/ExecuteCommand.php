<?php

namespace PhpMettlerToledo\SICS;

use PhpMettlerToledo\Connection;
use PhpMettlerToledo\Exception\CommandException;
use ReflectionClass;

class ExecuteCommand
{
    private Connection $_connection;
    private string $_commandListRegex;

    function __construct(Connection $connection)
    {
        $this->_connection = $connection;

        // Cache the command list regex to avoid reflection on every call
        $commandList = (new ReflectionClass(Commands::class))->getConstants();
        unset($commandList['CR_LF']);
        $this->_commandListRegex = '^(' . join('|', $commandList) . ')';
    }

    /**
     * Execute a command and return the raw response
     * @throws CommandException
     */
    private function executeCommand(string $command): string
    {
        $stream = $this->_connection->getStream();
        if (!is_resource($stream)) {
            throw new CommandException('Connection stream is not available');
        }

        fputs($stream, $command . Commands::CR_LF);
        $result = stream_get_line($stream, 1024, Commands::CR_LF);

        if ($result === false) {
            throw new CommandException('Failed to read response from scale');
        }

        $this->checkErrorResponse($result);
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function readCommandsAvailable(): array
    {
        $stream = $this->_connection->getStream();
        if (!is_resource($stream)) {
            throw new CommandException('Connection stream is not available');
        }

        fputs($stream, Commands::READ_COMMANDS_AVAILABLE . Commands::CR_LF);
        $result = [];
        for ($i = 0; $i < 100; $i++) {
            $line = stream_get_line($stream, 1024, Commands::CR_LF);
            if ($line === false) break;
            $line = trim(str_replace(['I0 A', 'I0 B'], '', $line));
            if (!empty($line)) $result[] = $line;
        }
        return $result;
    }

    /**
     * @throws CommandException
     */
    public function readWeightAndStatus(): float
    {
        $result = $this->executeCommand(Commands::READ_WEIGHT_AND_STATUS);
        return floatval(trim(str_replace(['SIX1'], '', $result)));
    }

    /**
     * @throws CommandException
     */
    public function readTareWeight(): float
    {
        $result = $this->executeCommand(Commands::READ_TARE_WEIGHT);
        return floatval(trim(str_replace(['TA'], '', $result)));
    }

    /**
     * @throws CommandException
     */
    public function readNetWeight(): float
    {
        $result = $this->executeCommand(Commands::READ_NET_WEIGHT);
        return floatval(trim(str_replace(['S S', 'S D', 'kg', 'g'], '', $result)));
    }

    /**
     * @throws CommandException
     */
    public function zeroStable(): void
    {
        $this->executeCommand(Commands::ZERO_STABLE_COMMAND);
    }

    /**
     * @throws CommandException
     */
    public function zeroImmediately(): void
    {
        $this->executeCommand(Commands::ZERO_IMMEDIATELY_COMMAND);
    }

    /**
     * @throws CommandException
     */
    public function tareStable(): void
    {
        $this->executeCommand(Commands::TARE_STABLE_COMMAND);
    }

    /**
     * @throws CommandException
     */
    public function tareImmediately(): void
    {
        $this->executeCommand(Commands::TARE_IMMEDIATELY_COMMAND);
    }

    /**
     * @throws CommandException
     */
    public function clearTare(): void
    {
        $this->executeCommand(Commands::CLEAR_TARE_COMMAND);
    }

    /**
     * @throws CommandException
     */
    public function readFirmwareRevision(): string
    {
        $result = $this->executeCommand(Commands::READ_FIRMWARE_REVISION);
        return trim(str_replace(['I3 A', '"'], '', $result));
    }

    /**
     * @throws CommandException
     */
    public function readSerialNumber(): string
    {
        $result = $this->executeCommand(Commands::READ_SERIAL_NUMBER);
        return trim(str_replace(['I4 A', '"'], '', $result));
    }

    /**
     * @throws CommandException
     */
    private function checkErrorResponse(string $response): void
    {
        $err = substr($response, 0, 2);
        switch ($err) {
            case 'ES':
                throw new CommandException('Syntax error');
            case 'ET':
                throw new CommandException('Transmission error');
            case 'EL':
                throw new CommandException('Logical error');
        }

        if (preg_match($this->_commandListRegex . ' [I]', $response)) {
            throw new CommandException('Command understood but currently not executable');
        }

        if (preg_match($this->_commandListRegex . ' [L]', $response)) {
            throw new CommandException('Command understood but not executable (incorrect parameter)');
        }

        if (preg_match($this->_commandListRegex . ' [\u002D\\-]', $response)) {
            throw new CommandException('Balance in underload range');
        }

        if (preg_match($this->_commandListRegex . ' [\u002B\\+]', $response)) {
            throw new CommandException('Balance in overload range');
        }
    }
}
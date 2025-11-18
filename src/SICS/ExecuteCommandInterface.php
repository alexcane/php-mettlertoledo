<?php

namespace PhpMettlerToledo\SICS;

use PhpMettlerToledo\Exception\CommandException;

interface ExecuteCommandInterface
{
    /**
     * Retrieves a list of available commands from the scale
     * @return array
     * @throws CommandException
     */
    public function readCommandsAvailable(): array;

    /**
     * Reads the current weight and status from the scale
     * @return float
     * @throws CommandException
     */
    public function readWeightAndStatus(): float;

    /**
     * Reads the tare weight from the scale
     * @return float
     * @throws CommandException
     */
    public function readTareWeight(): float;

    /**
     * Reads the net weight from the scale
     * @return float
     * @throws CommandException
     */
    public function readNetWeight(): float;

    /**
     * Zeros the scale using a stable zero method
     * @return void
     * @throws CommandException
     */
    public function zeroStable(): void;

    /**
     * Zeros the scale immediately regardless of balance stability
     * @return void
     * @throws CommandException
     */
    public function zeroImmediately(): void;

    /**
     * Sets the tare on the scale using a stable method
     * @return void
     * @throws CommandException
     */
    public function tareStable(): void;

    /**
     * Sets the tare on the scale immediately regardless of balance stability
     * @return void
     * @throws CommandException
     */
    public function tareImmediately(): void;

    /**
     * Clears the current tare value on the scale
     * @return void
     * @throws CommandException
     */
    public function clearTare(): void;

    /**
     * Returns the balance SW version and type definition number
     * @return string
     * @throws CommandException
     */
    public function readFirmwareRevision(): string;

    /**
     * Returns the serial number of the scale
     * @return string
     * @throws CommandException
     */
    public function readSerialNumber(): string;
}

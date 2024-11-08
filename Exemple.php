<?php

try {
    $scale = new \PhpMettlerToledo\MTSICS('SCALE_IP_ADDRESS', 4305);
    $error = $scale->getError();
    if($scale->isConnected()){
        $commandsArray = $scale->readCommandsAvailable();
        $weight = $scale->readWeightAndStatus();
        $tareWeight = $scale->readTareWeight();
        $netWeight = $scale->readNetWeight();
        $scale->zeroStable();
        $scale->zeroImmediately();
        $scale->tareStable();
        $scale->tareImmediatly();
        $scale->clearTare();
        $firmware = $scale->readFirmwareRevision();
        $serial = $scale->readSerialNumber();
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
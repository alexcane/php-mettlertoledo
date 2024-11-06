<?php


try {
    $balance = new \PhpMettlerToledo\MTSICS('', 0);
} catch (\PhpMettlerToledo\Exception\ConnectionException $e) {

}
$balance->isConnected();
//$balance->readSerialNumber();
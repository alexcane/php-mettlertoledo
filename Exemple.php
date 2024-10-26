<?php


$balance = new \PhpMettlerToledo\MTSICS('',0);
$balance->isConnected();
$balance->readSerialNumber();
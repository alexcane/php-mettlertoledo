<?php

namespace PhpMettlerToledo\SICS;

class Commands
{
    const READ_WEIGHT_AND_STATUS = "SIX1";
    const READ_TARE_WEIGHT = "TA";
    const READ_NET_WEIGHT = "SI";
    const ZERO_STABLE_COMMAND = "Z";
    const ZERO_IMMEDIATELY_COMMAND = "ZI";
    const TARE_STABLE_COMMAND = "T";
    const TARE_IMMEDIATELY_COMMAND = "TI";
    const CLEAR_TARE_COMMAND = "TAC";
    const READ_FIRMWARE_REVISION = "I3";
    const READ_SERIAL_NUMBER = "I4";
    const CR_LF = "\r\n";
}
# PHPMettlerToledo
A PHP Library for network communication with Mettler Toledo balances and scales that use the Mettler Toledo Standard Interface Command Set (MT-SICS).
This works is based on
[repo atlantis-software/mt-sics](https://github.com/ricado-group/dotnet-mettlertoledo)
and
[repo ricado-group/dotnet-mettlertoledo](https://github.com/ricado-group/dotnet-mettlertoledo)

## Installation

Run `composer require alexcane/php-mettlertoledo`

## Example

Take a look in Example.php
```php
use PhpMettlerToledo\MTSICS;

$scale = new MTSICS('192.168.1.100', 4305);
$weight = $scale->readNetWeight();
```

## Documentation

| function                | returns value | description                                                                      |
|-------------------------|---------------|----------------------------------------------------------------------------------|
| readCommandsAvailable() | array         | Retrieves a list of available commands supported by the Mettler Toledo scale.    |
| readWeightAndStatus()   | float         | Reads the current weight and status from the scale.                              |
| readTareWeight()        | float         | Reads the tare weight from the scale.                                            |
| readNetWeight()         | float         | Reads the net weight from the scale, irrespective of balance stability.          |
| zeroStable()            | bool          | Zeros the scale using a stable zero method.                                      |
| zeroImmediately()       | bool          | Zeros the balance immediately regardless the stability of the balance.           |
| tareStable()            | bool          | Sets the tare on the scale using a stable method.                                |
| tareImmediatly()        | bool          | Sets the tare on the scale immediately regardless the stability of the balance.  |
| clearTare()             | bool          | Clears the current tare value on the scale.                                      |
| readFirmwareRevision()  | string        | Returns the balance SW version and type definition number.                       |
| readSerialNumber()      | string        | Returns the serial number of the scale.                                          |
| getError()              | string        | Returns the last error message encountered.                                      |

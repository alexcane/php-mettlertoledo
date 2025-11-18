# PHPMettlerToledo
A PHP Library for network communication with Mettler Toledo balances and scales that use the Mettler Toledo Standard Interface Command Set (MT-SICS).
This works is based on
[repo atlantis-software/mt-sics](https://github.com/ricado-group/dotnet-mettlertoledo)
and
[repo ricado-group/dotnet-mettlertoledo](https://github.com/ricado-group/dotnet-mettlertoledo)

## Requirements

- PHP 8.1 or higher
- ext-mbstring

## Installation

Run `composer require alexcane/php-mettlertoledo`

## Example

Take a look in Example.php
```php
use PhpMettlerToledo\MTSICS;

$scale = new MTSICS('192.168.1.100', 4305);
if($scale->isConnected()){
    $weight = $scale->readNetWeight();
} else {
    $error = $scale->getError()
}
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
| tareImmediately()       | bool          | Sets the tare on the scale immediately regardless the stability of the balance.  |
| clearTare()             | bool          | Clears the current tare value on the scale.                                      |
| readFirmwareRevision()  | string        | Returns the balance SW version and type definition number.                       |
| readSerialNumber()      | string        | Returns the serial number of the scale.                                          |
| getError()              | string        | Returns the last error message encountered.                                      |

## Testing

### Unit Testing (without hardware)

As of v2.0.0, you can use dependency injection to test your code without requiring a physical scale:

```php
use PhpMettlerToledo\MTSICS;
use PhpMettlerToledo\SICS\ExecuteCommandInterface;

// Create a mock for testing
$mockExecuteCommand = $this->createMock(ExecuteCommandInterface::class);
$mockExecuteCommand->method('readNetWeight')->willReturn(123.45);

// Inject the mock
$scale = new MTSICS('192.168.1.100', 4305, $mockExecuteCommand);
$weight = $scale->readNetWeight(); // Returns 123.45
```

### Integration Testing (with hardware)

To run integration tests with a physical scale, update the IP address in `tests/PhpMettlerToledo/CommandTest.php` and run:

```bash
vendor/bin/phpunit tests/PhpMettlerToledo/CommandTest.php
```

Note: `CommandTest.php` is excluded from the default test suite as it requires physical hardware.

## Upgrade Guide

### From v1.x to v2.0

**Breaking change**: Minimum PHP version is now 8.1.

If you're still on PHP 7.4 or 8.0, you need to either:
- Upgrade your PHP version to 8.1 or higher, OR
- Stay on v1.x of this library (which will no longer receive updates)

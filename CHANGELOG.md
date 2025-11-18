# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-11-18

### Breaking Changes
- **Minimum PHP version is now 8.1** (previously 7.4)
- Dropped support for PHP 7.4 and 8.0 (both End of Life)

### Added
- Dependency injection support via `ExecuteCommandInterface`
- Unit tests with PHPUnit mocks (tests no longer require physical scale hardware)
- New interface `ExecuteCommandInterface` for better testability and extensibility
- Optional third parameter in `MTSICS` constructor to inject custom `ExecuteCommandInterface` implementations

### Changed
- `ExecuteCommand` now implements `ExecuteCommandInterface`
- `MTSICS::$_exec` property type changed from `ExecuteCommand` to `ExecuteCommandInterface`

### Fixed
- Fixed typo in test method name: `testTareImmediatlyIsTrue()` → `testTareImmediatelyIsTrue()`

## [1.0.0] - Initial Release

### Added
- TCP socket communication with Mettler Toledo scales via MT-SICS protocol
- Support for weight reading commands (S, SI, SIX1, TA)
- Support for zero and tare commands (Z, ZI, T, TI, TAC)
- Support for device information commands (I3, I4)
- Error handling with custom exceptions
- Connection state management
- PHPUnit test suite

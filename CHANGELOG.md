# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/)
and this project adheres to [Semantic Versioning](https://semver.org/).

## [2.0.0] - 2024-02-22

### Added

- Class `Ghostwriter\Collection\Interface\CollectionInterface` has been added
- Class `Ghostwriter\Collection\Interface\ExceptionInterface` has been added
- Method `Ghostwriter\Collection\Collection::from()` was added
- Method `Ghostwriter\Collection\Collection::new()` was added

### Removed

- Class `Ghostwriter\Collection\ExceptionInterface` has been deleted
- Method `Ghostwriter\Collection\Collection::fromGenerator()` was removed
- Method `Ghostwriter\Collection\Collection::fromIterable()` was removed

### Changed

- Exception class `Ghostwriter\Collection\Exception\FirstValueNotFoundException` implements `Ghostwriter\Collection\Interface\ExceptionInterface`
- Exception class `Ghostwriter\Collection\Exception\LengthMustBePositiveIntegerException` implements `Ghostwriter\Collection\Interface\ExceptionInterface`
- Exception class `Ghostwriter\Collection\Exception\OffsetMustBePositiveIntegerException` implements `Ghostwriter\Collection\Interface\ExceptionInterface`

# Collection

[![Compliance](https://github.com/ghostwriter/collection/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/collection/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/collection?color=8892bf)](https://www.php.net/supported-versions)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/collection/coverage.svg)](https://shepherd.dev/github/ghostwriter/collection)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/collection)](https://packagist.org/packages/ghostwriter/collection)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/collection?color=blue)](https://packagist.org/packages/ghostwriter/collection)

Provides a Collection implementation for PHP.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/collection
```

## Usage

```php
/** @var \Ghostwriter\Collection\Collection $collection */
$collection = Collection::fromIterable([1, 2, 3])
    ->append([4, 5, 6, 7, 8, 9])
    ->map(static fn ($v): int => $v * 10)
    ->filter(static fn ($v): bool => 0 === $v % 20);
    
$collection->toArray();  // [20, 40, 60, 80]
$collection->drop(1)     // [40, 60, 80]
           ->take(2)     // [40, 60]
           ->slice(1, 1) // [60]
           ->toArray();  // [60]
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email `nathanael.esayeas@protonmail.com` instead of using the issue tracker.

## Sponsors

[![ghostwriter's GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsors&logo=GitHub%20Sponsors)](https://github.com/sponsors/ghostwriter)

Maintaining open source software is a thankless, time-consuming job.

Sponsorships are one of the best ways to contribute to the long-term sustainability of an open-source licensed project.

Please consider giving back, to fund the continued development of `ghostwriter/collection`, by sponsoring me here on GitHub.

[[Become a GitHub Sponsor](https://github.com/sponsors/ghostwriter)]

### For Developers

Please consider helping your company become a GitHub Sponsor, to support the open-source licensed project that runs your business.

## Thank you

Thank you for freely sharing your knowledge, insight and free time with me in [Laminas Chat](https://laminas.dev/chat) and writing very detailed [Technical Blog](https://www.garfieldtech.com/). I absolutely appreciate you all, and the work you've done.

- [Larry Garfield](https://github.com/crell)
- [Aleksei Khudiakov](https://github.com/xerkus)
- [Andi R??ckauer](https://github.com/arueckauer)

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/collection/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.

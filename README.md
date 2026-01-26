# Collection

[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/collection&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Automation](https://github.com/ghostwriter/collection/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/collection/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/collection?color=8892bf)](https://www.php.net/supported-versions)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/collection?color=blue)](https://packagist.org/packages/ghostwriter/collection)

Provides a Collection implementation for PHP.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/collection
```

### Star ⭐️ this repo if you find it useful

You can also star (🌟) this repo to find it easier later.

## Usage

```php
/** @var \Ghostwriter\Collection\Collection $collection */
$collection = Collection::new([1, 2, 3])
    ->append([4, 5, 6, 7, 8, 9])
    ->map(static fn ($v): int => $v * 10)
    ->filter(static fn ($v): bool => 0 === $v % 20);
    
$collection->toArray();  // [20, 40, 60, 80]
$collection->drop(1)     // [40, 60, 80]
           ->take(2)     // [40, 60]
           ->slice(1, 1) // [60]
           ->toArray();  // [60]
```

### Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/collection/contributors)

## Thank you

Thank you for freely sharing your knowledge, insight and free time with me. I am grateful for your help and support.

- [Larry Garfield](https://github.com/crell)
- [Aleksei Khudiakov](https://github.com/xerkus)
- [Andi Rückauer](https://github.com/arueckauer)


### Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

### License

Please see [LICENSE](./LICENSE) for more information on the license that applies to this project.

### Security

Please see [SECURITY.md](./SECURITY.md) for more information on security disclosure process.

# Collection

[![Compliance](https://github.com/ghostwriter/collection/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/collection/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/collection?color=8892bf)](https://www.php.net/supported-versions)
[![Mutation Coverage](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fghostwriter%2Fcollection%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/ghostwriter/collection/main)
[![Code Coverage](https://codecov.io/gh/ghostwriter/collection/branch/main/graph/badge.svg)](https://codecov.io/gh/ghostwriter/collection)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/collection/coverage.svg)](https://shepherd.dev/github/ghostwriter/collection)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/collection)](https://packagist.org/packages/ghostwriter/collection)
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

## API

``` php
/**
 * @template TValue
 *
 * @extends IteratorAggregate<TValue>
 */
interface CollectionInterface extends Countable, IteratorAggregate
{
    /**
     * @param iterable<TValue> $iterable
     *
     * @return self<TValue>
     */
    public function append(iterable $iterable = []): self;

    /**
     * @template TContains
     *
     * @param Closure(TValue):bool|TContains $functionOrValue
     */
    public function contains(mixed $functionOrValue): bool;

    public function count(): int;

    /**
     * @param int<0,max> $length
     *
     * @throws LengthMustBePositiveIntegerException
     * @throws OffsetMustBePositiveIntegerException
     *
     * @return self<TValue>
     *
     */
    public function drop(int $length): self;

    /**
     * @param Closure(TValue):void $function
     */
    public function each(Closure $function): void;

    /**
     * @param Closure(TValue):bool $function
     *
     * @return self<TValue>
     */
    public function filter(Closure $function): self;

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @throws FirstValueNotFoundException If no value is found
     *
     * @return ?TValue
     *
     */
    public function first(Closure $function = null): mixed;

    /**
     * @return Generator<TValue>
     */
    public function getIterator(): Generator;

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @return null|TValue
     */
    public function last(Closure $function = null): mixed;

    /**
     * @template TMap
     *
     * @param Closure(TValue):TMap $function
     *
     * @return self<TMap>
     */
    public function map(Closure $function): self;

    /**
     * @template TAccumulator
     *
     * @param ?TAccumulator                                  $accumulator
     * @param Closure(null|TAccumulator,TValue):TAccumulator $function
     *
     * @return ?TAccumulator
     */
    public function reduce(Closure $function, mixed $accumulator = null): mixed;

    /**
     * @param int<0,max> $offset
     * @param int<0,max> $length
     *
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     *
     * @return self<TValue>
     *
     */
    public function slice(int $offset, int $length = PHP_INT_MAX): self;

    /**
     * @param int<0,max> $length
     *
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     *
     * @return self<TValue>
     *
     */
    public function take(int $length): self;

    /**
     * @return array<TValue>
     */
    public function toArray(): array;

    /**
     * @param Closure():Generator $generator
     *
     * @return self<TValue>
     */
    public static function from(Closure $generator): self;

    /**
     * @return self<TValue>
     */
    public static function new(iterable $iterable = []): self;
}
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

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Thank you

Thank you for freely sharing your knowledge, insight and free time with me. I am grateful for your help and support.

- [Larry Garfield](https://github.com/crell)
- [Aleksei Khudiakov](https://github.com/xerkus)
- [Andi Rückauer](https://github.com/arueckauer)

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/collection/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.

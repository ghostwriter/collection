<?php

declare(strict_types=1);

namespace Ghostwriter\Collection;

use Closure;
use Countable;
use Generator;
use Ghostwriter\Collection\Exception\FirstValueNotFoundException;
use Ghostwriter\Collection\Exception\LengthMustBePositiveIntegerException;
use Ghostwriter\Collection\Exception\OffsetMustBePositiveIntegerException;
use IteratorAggregate;
use SplFixedArray;
use function iterator_to_array;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<TValue>
 *
 * @see \Ghostwriter\Collection\Tests\Unit\CollectionTest
 */
final class Collection implements Countable, IteratorAggregate
{
    /**
     * @param SplFixedArray<TValue> $storage
     */
    private function __construct(
        private readonly SplFixedArray $storage
    ) {
    }

    /**
     * @param iterable<TValue> $iterable
     *
     * @return self<TValue>
     */
    public function append(iterable $iterable = []): self
    {
        if ([] === $iterable) {
            return $this;
        }

        return self::fromGenerator(function () use ($iterable): Generator {
            foreach ($this->storage as $value) {
                yield $value;
            }

            foreach ($iterable as $value) {
                yield $value;
            }
        });
    }

    /**
     * @template TContains
     *
     * @param Closure(TValue):bool|TContains $functionOrValue
     */
    public function contains(mixed $functionOrValue): bool
    {
        /** @var Closure(TValue):bool $function */
        $function = $functionOrValue instanceof Closure ?
            $functionOrValue :
            static fn (mixed $value): bool => $value === $functionOrValue;

        return (bool) $this->filter($function)->count();
    }

    public function count(): int
    {
        return $this->storage->count();
    }

    /**
     * @param int<0,max> $length
     *
     * @return self<TValue>
     *
     * @throws LengthMustBePositiveIntegerException
     * @throws OffsetMustBePositiveIntegerException
     */
    public function drop(int $length): self
    {
        return $this->slice($length);
    }

    /**
     * @param Closure(TValue):void $function
     */
    public function each(Closure $function): void
    {
        foreach ($this->storage as $value) {
            $function($value);
        }
    }

    /**
     * @param Closure(TValue):bool $function
     *
     * @return self<TValue>
     */
    public function filter(Closure $function): self
    {
        return self::fromGenerator(function () use ($function): Generator {
            foreach ($this->storage as $value) {
                if (!$function($value)) {
                    continue;
                }

                yield $value;
            }
        });
    }

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @return ?TValue
     *
     * @throws FirstValueNotFoundException If no value is found
     */
    public function first(Closure $function = null): mixed
    {
        $function ??= static fn (mixed $value): bool => null !== $value;

        foreach ($this->filter($function) as $value) {
            return $value;
        }

        throw new FirstValueNotFoundException();
    }

    /**
     * @param Closure():Generator $generator
     *
     * @return self<TValue>
     */
    public static function fromGenerator(Closure $generator): self
    {
        /** @var Closure():Generator<TValue> $generator */
        $collection = $generator();

        /** @var array<int,TValue> $asArray */
        $asArray = iterator_to_array($collection);

        return new self(SplFixedArray::fromArray($asArray, false));
    }

    /**
     * @return self<TValue>
     */
    public static function fromIterable(iterable $iterable = []): self
    {
        /* @var iterable<TValue> $iterable */
        return self::fromGenerator(static fn () => yield from $iterable);
    }

    /**
     * @return Generator<TValue>
     */
    public function getIterator(): Generator
    {
        yield from $this->storage;
    }

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @return TValue|null
     */
    public function last(Closure $function = null): mixed
    {
        $last = null;

        $function ??= static fn (mixed $value): bool => null !== $value;

        foreach ($this->filter($function) as $value) {
            $last = $value;
        }

        return $last;
    }

    /**
     * @template TMap
     *
     * @param Closure(TValue):TMap $function
     *
     * @return self<TMap>
     */
    public function map(Closure $function): self
    {
        return self::fromGenerator(function () use ($function): Generator {
            foreach ($this->storage as $value) {
                yield $function($value);
            }
        });
    }

    /**
     * @template TAccumulator
     *
     * @param ?TAccumulator                                   $accumulator
     * @param Closure(TAccumulator|null,TValue):TAccumulator $function
     *
     * @return ?TAccumulator
     */
    public function reduce(Closure $function, mixed $accumulator = null): mixed
    {
        foreach ($this->storage as $value) {
            $accumulator = $function($accumulator, $value);
        }

        return $accumulator;
    }

    /**
     * @param int<0,max> $offset
     * @param int<0,max> $length
     *
     * @psalm-suppress DocblockTypeContradiction
     *
     * @return self<TValue>
     *
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     */
    public function slice(int $offset, int $length = PHP_INT_MAX): self
    {
        if ($offset < 0) {
            throw new OffsetMustBePositiveIntegerException();
        }

        if ($length < 0) {
            throw new LengthMustBePositiveIntegerException();
        }

        return self::fromGenerator(
            function () use ($offset, $length): Generator {
                $total = 0;

                if ($total !== $length) {
                    $limit = $offset + $length;

                    foreach ($this as $current) {
                        if ($total++ < $offset) {
                            continue;
                        }

                        yield $current;

                        if ($total >= $limit) {
                            break;
                        }
                    }
                }
            }
        );
    }

    /**
     * @param int<0,max> $length
     *
     * @return self<TValue>
     *
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     */
    public function take(int $length): self
    {
        return $this->slice(0, $length);
    }

    /**
     * @return array<TValue>
     */
    public function toArray(): array
    {
        return $this->storage->toArray();
    }
}

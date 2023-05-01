<?php

declare(strict_types=1);

namespace Ghostwriter\Collection;

use Closure;
use Countable;
use Generator;
use Ghostwriter\Collection\Exception\CollectionException;
use Ghostwriter\Option\Some;
use Ghostwriter\Option\SomeInterface;
use IteratorAggregate;
use Traversable;

use const PHP_INT_MAX;

/**
 * @template TValue
 *
 * @implements IteratorAggregate<int,TValue>
 *
 * @see \Ghostwriter\Collection\Tests\Unit\CollectionTest
 */
final class Collection implements Countable, IteratorAggregate
{
    /** @param SomeInterface<Closure():Traversable<int,TValue>> $some */
    private function __construct(
        private SomeInterface $some
    ) {
    }

    /**
     * @param iterable<TValue> $iterable
     */
    public function append(iterable $iterable = []): self
    {
        if ($iterable === []) {
            return $this;
        }

        return self::fromGenerator(function () use ($iterable): Generator {
            yield from $this;
            yield from $iterable;
        });
    }

    /**
     * @template TContains
     *
     * @param Closure(TValue,int):bool|TContains $functionOrValue
     */
    public function contains(mixed $functionOrValue): bool
    {
        $function = $functionOrValue instanceof Closure ?
            $functionOrValue :
            static fn (mixed $value, mixed $_): bool => $value === $functionOrValue;

        foreach ($this->getIterator() as $key => $value) {
            if ($function($value, $key)) {
                return true;
            }
        }

        return false;
    }

    public function count(): int
    {
        return iterator_count($this);
    }

    /**
     * @param int<0,max> $length
     */
    public function drop(int $length): self
    {
        return $this->slice($length);
    }

    /**
     * @param Closure(TValue,int):bool $function
     */
    public function filter(Closure $function): self
    {
        return self::fromGenerator(function () use ($function): Generator {
            foreach ($this->getIterator() as $key => $value) {
                if ($function($value, $key)) {
                    yield $value;
                }
            }
        });
    }

    /**
     * @param ?Closure(TValue,int):bool $function
     *
     * @return ?TValue
     */
    public function first(Closure $function = null): mixed
    {
        $function ??= static fn (mixed $value, int $_): bool => $value !== null;
        foreach ($this->getIterator() as $key => $value) {
            if ($function($value, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @template TGenerator
     *
     * @param Closure():Traversable<int,TGenerator> $generator
     */
    public static function fromGenerator(Closure $generator): self
    {
        /** @var Some<Closure():Traversable<int,TGenerator>> $some */
        $some = Some::create($generator);

        return new self($some);
    }

    public static function fromIterable(iterable $iterable = []): self
    {
        /**
         * @var iterable<TValue> $iterable
         */
        return self::fromGenerator(static fn () => yield from $iterable);
    }

    public function getIterator(): Traversable
    {
        $closure = $this->some->unwrap();

        yield from $closure();
    }

    /**
     * @param ?Closure(TValue,int):bool $function
     *
     * @return null|TValue
     */
    public function last(?Closure $function = null): mixed
    {
        $last = null;
        $function ??= static fn (mixed $value, int $_): bool => $value !== null;
        foreach ($this->getIterator() as $key => $value) {
            if ($function($value, $key)) {
                $last = $value;
            }
        }

        return $last;
    }

    /**
     * @template TMap
     *
     * @param Closure(TValue,int):TMap $function
     */
    public function map(Closure $function): self
    {
        return self::fromGenerator(function () use ($function): Generator {
            foreach ($this->getIterator() as $key => $value) {
                yield $function($value, $key);
            }
        });
    }

    /**
     * @template TAccumulator
     *
     * @param ?TAccumulator                                      $accumulator
     * @param Closure(null|TAccumulator,TValue,int):TAccumulator $function
     *
     * @return ?TAccumulator
     */
    public function reduce(Closure $function, mixed $accumulator = null): mixed
    {
        foreach ($this->getIterator() as $key => $value) {
            $accumulator = $function($accumulator, $value, $key);
        }

        return $accumulator;
    }

    /**
     * @param int<0,max> $offset
     * @param int<0,max> $length
     *
     * @throws CollectionException If $offset or $length are negative
     *
     * @noinspection PhpConditionAlreadyCheckedInspection
     *
     * @psalm-suppress DocblockTypeContradiction
     */
    public function slice(int $offset, int $length = PHP_INT_MAX): self
    {
        if ($offset < 0) {
            throw new CollectionException('$offset must be positive');
        }

        if ($length < 0) {
            throw new CollectionException('$length must be positive');
        }

        return self::fromGenerator(
            function () use ($offset, $length): Generator {
                $total = 0;
                if ($total === $length) {
                    return;
                }

                $limit = $offset + $length;
                foreach ($this->getIterator() as $current) {
                    if ($total++ < $offset) {
                        continue;
                    }

                    yield $current;
                    if ($total >= $limit) {
                        break;
                    }
                }
            }
        );
    }

    /**
     * @param int<0,max> $length
     */
    public function take(int $length): self
    {
        return $this->slice(0, $length);
    }

    /**
     * @return array<int,TValue>
     */
    public function toArray(): array
    {
        return iterator_to_array($this, false);
    }
}

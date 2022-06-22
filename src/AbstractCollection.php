<?php

declare(strict_types=1);

namespace Ghostwriter\Collection;

use Closure;
use Generator;
use Ghostwriter\Collection\Contract\CollectionInterface;
use Ghostwriter\Collection\Exception\InvalidArgumentException;
use SplFixedArray;
use const PHP_INT_MAX;

/**
 * @template TValue
 * @implements CollectionInterface<TValue>
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var Closure():Generator<TValue>
     */
    private Closure $generator;

    /**
     * @param Closure():Generator<TValue> $generator
     */
    final private function __construct(Closure $generator)
    {
        /** @param SplFixedArray<TValue> $iterator */
        $this->generator = (
            static fn (SplFixedArray $iterator): Closure => static fn (): Generator => yield from $iterator
        )(SplFixedArray::fromArray(iterator_to_array($generator(), false)));
    }

    public function append(iterable $iterable = []): static
    {
        return new (static::class)(function () use ($iterable): Generator {
            yield from $this;
            yield from $iterable;
        });
    }

    public function contains(mixed $value, ?Closure $function = null): bool
    {
        $function ??= static fn (mixed $current, mixed $value): bool => $current === $value;
        foreach ($this as $current) {
            if (true === $function($current, $value)) {
                return true;
            }
        }

        return false;
    }

    public function count(): int
    {
        return iterator_count($this);
    }

    public function drop(int $length): static
    {
        return $this->slice($length);
    }

    public function filter(Closure $function): static
    {
        return new (static::class)(function () use ($function): Generator {
            foreach ($this as $value) {
                if (true === $function($value)) {
                    yield $value;
                }
            }
        });
    }

    public function first(Closure $function = null): mixed
    {
        $function ??= static fn (mixed $_): bool => true;
        foreach ($this as $value) {
            if (true === $function($value)) {
                return $value;
            }
        }

        return null;
    }

    public static function fromGenerator(Closure $generator): static
    {
        /**
         * @var Closure():Generator<TValue> $iterable
         */
        return new (static::class)($generator);
    }

    public static function fromIterable(iterable $iterable = []): static
    {
        /**
         * @var iterable<TValue> $iterable
         */
        return new (static::class)(static fn () => yield from $iterable);
    }

    public function getIterator(): Generator
    {
        yield from ($this->generator)();
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function last(?Closure $function = null): mixed
    {
        $last = null;
        $function ??= static fn (mixed $_): bool => true;
        foreach ($this as $value) {
            if (true === $function($value)) {
                $last = $value;
            }
        }

        return $last;
    }

    public function map(Closure $function): static
    {
        return new (static::class)(function () use ($function) {
            foreach ($this as $value) {
                yield $function($value);
            }
        });
    }

    public function reduce(Closure $function, mixed $accumulator = null): mixed
    {
        foreach ($this as $value) {
            /** @psalm-suppress PossiblyNullArgument */
            $accumulator = $function($accumulator, $value);
        }
        return $accumulator;
    }

    public function slice(int $offset, int $length = PHP_INT_MAX): static
    {
        if ($offset < 0) {
            throw new InvalidArgumentException('$offset must be positive');
        }
        if ($length < 0) {
            throw new InvalidArgumentException('$length must be positive');
        }
        return new (static::class)(
            function () use ($offset, $length): Generator {
                $count = 0;
                $max = $offset + $length;
                if ($count === $length) {
                    return;
                }
                foreach ($this as $current) {
                    if ($count++ < $offset) {
                        continue;
                    }
                    yield $current;
                    if ($count >= $max) {
                        return;
                    }
                }
            }
        );
    }

    public function take(int $length): static
    {
        return $this->slice(0, $length);
    }

    public function toArray(): array
    {
        return iterator_to_array($this, false);
    }
}

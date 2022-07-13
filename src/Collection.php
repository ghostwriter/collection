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
final class Collection implements CollectionInterface
{
    /** @param SplFixedArray<TValue> $generator */
    private SplFixedArray $array;

    /**
     * @param Closure():Generator<TValue> $generator
     */
    private function __construct(Closure $generator)
    {
        /** @param SplFixedArray<TValue> $iterable */
        $this->array = SplFixedArray::fromArray(iterator_to_array($generator(), false));
    }

    public function append(iterable $iterable = []): self
    {
        if ([] === $iterable) {
            return $this;
        }

        return new (self::class)(function () use ($iterable): Generator {
            yield from $this;
            yield from $iterable;
        });
    }

    public function contains(mixed $value, ?Closure $function = null): bool
    {
        $function ??= static fn (mixed $current, mixed $value): bool => $current === $value;
        foreach ($this as $current) {
            if (! $function($current, $value)) {
                continue;
            }
            return true;
        }

        return false;
    }

    public function count(): int
    {
        return $this->array->count();
    }

    public function drop(int $length): self
    {
        return $this->slice($length);
    }

    public function filter(Closure $function): self
    {
        return new self(function () use ($function): Generator {
            foreach ($this as $value) {
                if (! $function($value)) {
                    continue;
                }
                yield $value;
            }
        });
    }

    public function first(Closure $function = null): mixed
    {
        $function ??= static fn (mixed $_): bool => true;
        foreach ($this as $value) {
            if (! $function($value)) {
                continue;
            }
            return $value;
        }

        return null;
    }

    public static function fromGenerator(Closure $generator): self
    {
        /**
         * @var Closure():Generator<TValue> $generator
         */
        return new self($generator);
    }

    public static function fromIterable(iterable $iterable = []): self
    {
        /**
         * @var iterable<TValue> $iterable
         */
        return new self(static fn () => yield from $iterable);
    }

    public function getIterator(): Generator
    {
        yield from $this->array;
    }

    public function last(?Closure $function = null): mixed
    {
        $last = null;
        $function ??= static fn (mixed $_): bool => true;
        foreach ($this as $value) {
            if (! $function($value)) {
                continue;
            }
            $last = $value;
        }

        return $last;
    }

    public function map(Closure $function): self
    {
        return new self(function () use ($function): Generator {
            foreach ($this as $value) {
                yield $function($value);
            }
        });
    }

    public function reduce(Closure $function, mixed $accumulator = null): mixed
    {
        foreach ($this as $value) {
            $accumulator = $function($accumulator, $value);
        }

        return $accumulator;
    }

    public function slice(int $offset, int $length = PHP_INT_MAX): self
    {
        if ($offset < 0) {
            throw new InvalidArgumentException('$offset must be positive');
        }
        if ($length < 0) {
            throw new InvalidArgumentException('$length must be positive');
        }
        return new self(
            function () use ($offset, $length): Generator {
                $count = 0;
                $maxCount = $offset + $length;
                if ($count === $length) {
                    return;
                }
                foreach ($this as $current) {
                    if ($count++ < $offset) {
                        continue;
                    }
                    yield $current;
                    if ($count >= $maxCount) {
                        return;
                    }
                }
            }
        );
    }

    public function take(int $length): self
    {
        return $this->slice(0, $length);
    }

    public function toArray(): array
    {
        return iterator_to_array($this, false);
    }
}

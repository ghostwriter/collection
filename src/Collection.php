<?php

declare(strict_types=1);

namespace Ghostwriter\Collection;

use Closure;
use Generator;
use Ghostwriter\Collection\Contract\CollectionInterface;
use Ghostwriter\Collection\Exception\CollectionException;
use Ghostwriter\Option\Contract\OptionInterface;
use Ghostwriter\Option\Some;
use const PHP_INT_MAX;

/**
 * @template TValue
 *
 * @implements CollectionInterface<TValue>
 *
 * @see \Ghostwriter\Collection\Tests\Unit\CollectionTest
 */
final class Collection implements CollectionInterface
{
    /** @var OptionInterface<TValue> */
    private OptionInterface $option;

    /** @param Closure():Generator<TValue> $generator */
    private function __construct(Closure $generator)
    {
        /** @var OptionInterface<TValue> $this->option */
        $this->option = Some::create($generator);
    }

    public function append(iterable $iterable = []): self
    {
        if ([] === $iterable) {
            return $this;
        }

        return new self(function () use ($iterable): Generator {
            yield from $this;
            yield from $iterable;
        });
    }

    public function contains(mixed $value, ?Closure $function = null): bool
    {
        $function ??= static fn (mixed $current, mixed $value): bool => $current === $value;
        foreach ($this->getIterator() as $current) {
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

    public function drop(int $length): self
    {
        return $this->slice($length);
    }

    public function filter(Closure $function): self
    {
        return new self(function () use ($function): Generator {
            foreach ($this->getIterator() as $value) {
                if (true === $function($value)) {
                    yield $value;
                }
            }
        });
    }

    public function first(Closure $function = null): mixed
    {
        $function ??= static fn (mixed $_): bool => true;
        foreach ($this->getIterator() as $value) {
            if (true === $function($value)) {
                return $value;
            }
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
        yield from $this->option->unwrap()();
    }

    public function last(?Closure $function = null): mixed
    {
        $function ??= static fn (mixed $_): bool => true;
        foreach ($this->getIterator() as $value) {
            if (true === $function($value)) {
                $last = $value;
            }
        }
        return $last ?? null;
    }

    public function map(Closure $function): self
    {
        return new self(function () use ($function): Generator {
            foreach ($this->getIterator() as $value) {
                yield $function($value);
            }
        });
    }

    public function reduce(Closure $function, mixed $accumulator = null): mixed
    {
        foreach ($this->getIterator() as $value) {
            $accumulator = $function($accumulator, $value);
        }

        return $accumulator;
    }

    public function slice(int $offset, int $length = PHP_INT_MAX): self
    {
        if ($offset < 0) {
            throw new CollectionException('$offset must be positive');
        }

        if ($length < 0) {
            throw new CollectionException('$length must be positive');
        }

        return new self(
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

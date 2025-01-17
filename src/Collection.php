<?php

declare(strict_types=1);

namespace Ghostwriter\Collection;

use Closure;
use Generator;
use Ghostwriter\Collection\Exception\FirstValueNotFoundException;
use Ghostwriter\Collection\Exception\LengthMustBePositiveIntegerException;
use Ghostwriter\Collection\Exception\OffsetMustBePositiveIntegerException;
use Ghostwriter\Collection\Interface\CollectionInterface;
use Override;
use Tests\Unit\CollectionTest;

use const PHP_INT_MAX;

use function iterator_count;
use function iterator_to_array;

/**
 * @template TValue
 *
 * @implements CollectionInterface<TValue>
 *
 * @see CollectionTest
 */
final readonly class Collection implements CollectionInterface
{
    /**
     * @param list<TValue> $storage
     */
    private function __construct(
        private array $storage
    ) {}

    /**
     * @return self<TValue>
     */
    #[Override]
    public static function new(iterable $iterable = []): self
    {
        /** @var iterable<TValue> $iterable */
        return self::from(static fn () => yield from $iterable);
    }

    /**
     * @param iterable<TValue> $iterable
     *
     * @return self<TValue>
     */
    #[Override]
    public function append(iterable $iterable = []): self
    {
        if ([] === $iterable) {
            return $this;
        }

        return self::from(function () use ($iterable): Generator {
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
    #[Override]
    public function contains(mixed $functionOrValue): bool
    {
        /** @var Closure(TValue):bool $function */
        $function = match (true) {
            $functionOrValue instanceof Closure => $functionOrValue,
            default => static fn (mixed $value): bool => $value === $functionOrValue,
        };

        return (bool) $this->filter($function)
            ->count();
    }

    #[Override]
    public function count(): int
    {
        return iterator_count($this);
    }

    /**
     * @param int<0,max> $length
     *
     * @throws LengthMustBePositiveIntegerException
     * @throws OffsetMustBePositiveIntegerException
     *
     * @return self<TValue>
     *
     */
    #[Override]
    public function drop(int $length): self
    {
        return $this->slice($length);
    }

    /**
     * @param Closure(TValue):void $function
     */
    #[Override]
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
    #[Override]
    public function filter(Closure $function): self
    {
        return self::from(function () use ($function): Generator {
            foreach ($this->storage as $value) {
                if (! $function($value)) {
                    continue;
                }

                yield $value;
            }
        });
    }

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @throws FirstValueNotFoundException If no value is found
     *
     * @return ?TValue
     *
     */
    #[Override]
    public function first(?Closure $function = null): mixed
    {
        $function ??= static fn (mixed $value): bool => null !== $value;

        foreach ($this->filter($function) as $value) {
            return $value;
        }

        throw new FirstValueNotFoundException();
    }

    /**
     * @return Generator<TValue>
     */
    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->storage;
    }

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @return null|TValue
     */
    #[Override]
    public function last(?Closure $function = null): mixed
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
    #[Override]
    public function map(Closure $function): self
    {
        return self::from(function () use ($function): Generator {
            foreach ($this->storage as $value) {
                yield $function($value);
            }
        });
    }

    /**
     * @template TAccumulator
     *
     * @param Closure(null|TAccumulator,TValue):TAccumulator $function
     * @param ?TAccumulator                                  $accumulator
     *
     * @return ?TAccumulator
     */
    #[Override]
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
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     *
     * @return self<TValue>
     *
     */
    #[Override]
    public function slice(int $offset, int $length = PHP_INT_MAX): self
    {
        if (0 > $offset) {
            throw new OffsetMustBePositiveIntegerException();
        }

        if (0 > $length) {
            throw new LengthMustBePositiveIntegerException();
        }

        return self::from(
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
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     *
     * @return self<TValue>
     *
     */
    #[Override]
    public function take(int $length): self
    {
        return $this->slice(0, $length);
    }

    /**
     * @return list<TValue>
     */
    #[Override]
    public function toArray(): array
    {
        return $this->storage;
    }

    /**
     * @param Closure():Generator $generator
     *
     * @return self<TValue>
     */
    #[Override]
    public static function from(Closure $generator): self
    {
        /** @var Closure():Generator<TValue> $generator */
        $collection = $generator();

        /** @var array<int,TValue> $asArray */
        $asArray = iterator_to_array($collection);

        return new self($asArray);
    }
}

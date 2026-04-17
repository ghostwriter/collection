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

use function count;
use function iterator_to_array;

/**
 * @template TKey
 * @template TValue
 *
 * @implements CollectionInterface<TKey,TValue>
 *
 * @see CollectionTest
 */
final readonly class Collection implements CollectionInterface
{
    /** @param array<TKey,TValue> $storage */
    private function __construct(
        private array $storage
    ) {}

    /** @return self<TKey,TValue> */
    #[Override]
    public static function new(iterable $iterable = []): self
    {
        if ([] === $iterable) {
            return new self([]);
        }

        $storage = [];

        foreach ($iterable as $value) {
            $storage[] = $value;
        }

        return new self($storage);
    }

    /**
     * @param iterable<TKey,TValue> $iterable
     *
     * @return self<TKey,TValue>
     */
    #[Override]
    public function append(iterable $iterable = []): self
    {
        if ([] === $iterable) {
            return $this;
        }

        $storage = [...$this->storage];

        foreach ($iterable as $value) {
            $storage[] = $value;
        }

        return new self($storage);
    }

    /**
     * @template TContains
     *
     * @param Closure(TValue,TKey):bool|TContains $functionOrValue
     */
    #[Override]
    public function contains(mixed $functionOrValue): bool
    {
        /** @var Closure(TValue,TKey):bool $function */
        $function = match (true) {
            $functionOrValue instanceof Closure => $functionOrValue,
            default => static fn (mixed $value): bool => $value === $functionOrValue,
        };

        return $this->filter($function)->count() !== 0;
    }

    #[Override]
    public function count(): int
    {
        return count($this->storage);
    }

    /**
     * @param int<0,max> $length
     *
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     *
     * @return self<TKey,TValue>
     */
    #[Override]
    public function drop(int $length): self
    {
        return $this->slice($length);
    }

    /** @param Closure(TValue,TKey):void $function */
    #[Override]
    public function each(Closure $function): self
    {
        foreach ($this->storage as $key => $value) {
            $function($value, $key);
        }

        return $this;
    }

    /**
     * @param Closure(TValue,TKey):bool $function
     *
     * @return self<TValue>
     */
    #[Override]
    public function filter(Closure $function): self
    {
        $storage = [];

        foreach ($this->storage as $key => $value) {
            if ($function($value, $key) === true) {
                $storage[] = $value;
            }
        }

        return new self($storage);
    }

    /**
     * @param ?Closure(TValue,TKey):bool $function
     *
     * @throws FirstValueNotFoundException If no value is found
     *
     * @return ?TValue
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

    /** @return Generator<TKey,TValue> */
    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->storage;
    }

    /**
     * @param ?Closure(TValue,TKey):bool $function
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
     * @param Closure(TValue,TKey):TMap $function
     *
     * @return self<TMap>
     */
    #[Override]
    public function map(Closure $function): self
    {
        $storage = [];

        foreach ($this->storage as $key => $value) {
            $storage[] = $function($value, $key);
        }

        return new self($storage);
    }

    /**
     * @template TAccumulator
     *
     * @param Closure(null|TAccumulator,TValue,TKey):TAccumulator $function
     * @param ?TAccumulator                                       $accumulator
     *
     * @return ?TAccumulator
     */
    #[Override]
    public function reduce(Closure $function, mixed $accumulator = null): mixed
    {
        foreach ($this->storage as $key => $value) {
            $accumulator = $function($accumulator, $value, $key);
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
     * @return self<TKey,TValue>
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

        if (0 === $length) {
            return new self([]);
        }

        $storage = [];
        $limit = $offset + $length;
        $total = 0;

        foreach ($this->storage as $current) {
            if ($total++ < $offset) {
                continue;
            }

            $storage[] = $current;

            if ($total >= $limit) {
                break;
            }
        }

        return new self($storage);
    }

    /**
     * @param int<0,max> $length
     *
     * @throws LengthMustBePositiveIntegerException
     * @throws OffsetMustBePositiveIntegerException
     *
     * @return self<TKey,TValue>
     */
    #[Override]
    public function take(int $length): self
    {
        return $this->slice(0, $length);
    }

    /** @return array<TKey,TValue> */
    #[Override]
    public function toArray(): array
    {
        return $this->storage;
    }

    /**
     * @param Closure():Generator $generator
     *
     * @return self<TKey,TValue>
     */
    #[Override]
    public static function from(Closure $generator): self
    {
        return new self(iterator_to_array($generator()));
    }
}

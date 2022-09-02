<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Contract;

use Closure;
use Countable;
use Generator;
use Ghostwriter\Collection\Exception\CollectionException;
use IteratorAggregate;
use Traversable;
use const PHP_INT_MAX;

/**
 * @template TValue
 *
 * @extends Countable
 * @extends IteratorAggregate<array-key,TValue>
 */
interface CollectionInterface extends Countable, IteratorAggregate
{
    /**
     * @param iterable<TValue> $iterable
     */
    public function append(iterable $iterable): self;

    /**
     * @template TContains
     *
     * @param TContains                        $value
     * @param ?Closure(TValue, TContains):bool $function
     */
    public function contains(mixed $value, ?Closure $function = null): bool;

    public function count(): int;

    public function drop(int $length): self;

    /**
     * @param Closure(TValue):bool $function
     */
    public function filter(Closure $function): self;

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @return ?TValue
     */
    public function first(?Closure $function = null): mixed;

    public static function fromGenerator(Closure $generator): self;

    public static function fromIterable(iterable $iterable = []): self;

    /**
     * @return Generator<TValue>
     */
    public function getIterator(): Traversable;

    /**
     * @param ?Closure(TValue):bool $function
     *
     * @return null|TValue
     */
    public function last(?Closure $function = null): mixed;

    /**
     * @template TMap
     *
     * @param Closure(TValue):TMap $function
     */
    public function map(Closure $function): self;

    /**
     * @template TAccumulator
     *
     * @param null|TAccumulator                              $accumulator
     * @param Closure(null|TAccumulator,TValue):TAccumulator $function
     *
     * @return TAccumulator
     */
    public function reduce(Closure $function, mixed $accumulator = null): mixed;

    /** @throws CollectionException If $offset or $length are non-negative */
    public function slice(int $offset, int $length = PHP_INT_MAX): self;

    public function take(int $length): self;

    /**
     * @return array<array-key,TValue>
     */
    public function toArray(): array;
}

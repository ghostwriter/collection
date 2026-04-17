<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Interface;

use Closure;
use Countable;
use Generator;
use Ghostwriter\Collection\Exception\FirstValueNotFoundException;
use Ghostwriter\Collection\Exception\LengthMustBePositiveIntegerException;
use Ghostwriter\Collection\Exception\OffsetMustBePositiveIntegerException;
use IteratorAggregate;
use Override;

use const PHP_INT_MAX;

/**
 * @template TKey
 * @template TValue
 *
 * @extends IteratorAggregate<TKey,TValue>
 */
interface CollectionInterface extends Countable, IteratorAggregate
{
    /** @return self<TKey,TValue> */
    public static function new(iterable $iterable = []): self;

    /**
     * @param iterable<TKey,TValue> $iterable
     *
     * @return self<TKey,TValue>
     */
    public function append(iterable $iterable = []): self;

    /**
     * @template TContains
     *
     * @param Closure(TValue,TKey):bool|TContains $functionOrValue
     */
    public function contains(mixed $functionOrValue): bool;

    /** @return int<0,max> */
    #[Override]
    public function count(): int;

    /**
     * @param int<0,max> $length
     *
     * @throws LengthMustBePositiveIntegerException
     * @throws OffsetMustBePositiveIntegerException
     *
     * @return self<TValue>
     */
    public function drop(int $length): self;

    /** @param Closure(TValue,TKey):void $function */
    public function each(Closure $function): self;

    /**
     * @param Closure(TValue,TKey):bool $function
     *
     * @return self<TValue>
     */
    public function filter(Closure $function): self;

    /**
     * @param ?Closure(TValue,TKey):bool $function
     *
     * @throws FirstValueNotFoundException If no value is found
     *
     * @return ?TValue
     */
    public function first(?Closure $function = null): mixed;

    /** @return Generator<TValue> */
    #[Override]
    public function getIterator(): Generator;

    /**
     * @param ?Closure(TValue,TKey):bool $function
     *
     * @return null|TValue
     */
    public function last(?Closure $function = null): mixed;

    /**
     * @template TMap
     *
     * @param Closure(TValue,TKey):TMap $function
     *
     * @return self<TMap>
     */
    public function map(Closure $function): self;

    /**
     * @template TAccumulator
     *
     * @param Closure(null|TAccumulator,TValue,TKey):TAccumulator $function
     * @param ?TAccumulator                                       $accumulator
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
     * @return self<TKey,TValue>
     */
    public function slice(int $offset, int $length = PHP_INT_MAX): self;

    /**
     * @param int<0,max> $length
     *
     * @throws OffsetMustBePositiveIntegerException
     * @throws LengthMustBePositiveIntegerException
     *
     * @return self<TKey,TValue>
     */
    public function take(int $length): self;

    /** @return array<TKey,TValue> */
    public function toArray(): array;

    /**
     * @param Closure():Generator $generator
     *
     * @return self<TKey,TValue>
     */
    public static function from(Closure $generator): self;
}

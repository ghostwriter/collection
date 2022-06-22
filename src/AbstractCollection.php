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
}

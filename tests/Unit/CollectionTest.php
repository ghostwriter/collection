<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Tests\Unit;

use Generator;
use Ghostwriter\Collection\Collection;
use Ghostwriter\Collection\Exception\InvalidArgumentException;

/**
 * @coversDefaultClass \Ghostwriter\Collection\Collection
 *
 * @internal
 *
 * @small
 */
final class CollectionTest extends AbstractTestCase
{
    public function sliceDataProvider(): Generator
    {
        yield 'empty' => [[], [0], []];
        yield '[1,2,3] -> slice(0, -1)' => [[1, 2, 3], [0, -1], [1], true];
        yield '[1,2,3] -> slice(-1)' => [[1, 2, 3], [-1], [1], true];
        yield '[1,2,3] -> slice(0)' => [[1, 2, 3], [0], [1, 2, 3]];
        yield '[1,2,3] -> slice(0, 1)' => [[1, 2, 3], [0, 1], [1]];
        yield '[1,2,3] -> slice(1, 1)' => [[1, 2, 3], [1, 1], [2]];
        yield '[1,2,3] -> slice(1, PHP_MAX_INT)' => [[1, 2, 3], [1], [2, 3]];
        yield '[1,2,3] -> slice(1, 0)' => [[1, 2, 3], [1, 0], []];
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::append
     * @covers \Ghostwriter\Collection\Collection::count
     * @covers \Ghostwriter\Collection\Collection::filter
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::map
     * @covers \Ghostwriter\Collection\Collection::toArray
     */
    public function testAppendMapFilter(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertCount(3, $collection);

        $new = $collection->append([4, 5, 6, 7, 8, 9])
            ->map(static fn (int $v): int => $v * 10)
            ->filter(static fn (int $v): bool => 0 === $v % 20);

        // print_r([$new]);
        self::assertSame([20, 40, 60, 80], $new->toArray());
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::contains
     */
    public function testContains(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);

        self::assertTrue($collection->contains(2));

        self::assertTrue(
            $collection->contains(
                3,
                static fn (int $current, int $value): bool => 3 === $current && $current === $value
            )
        );

        self::assertFalse(
            $collection->contains(1, static fn (int $current, int $value): bool => $current > 10 && $value > 10)
        );
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::append
     * @covers \Ghostwriter\Collection\Collection::count
     * @covers \Ghostwriter\Collection\Collection::drop
     * @covers \Ghostwriter\Collection\Collection::filter
     * @covers \Ghostwriter\Collection\Collection::first
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::map
     * @covers \Ghostwriter\Collection\Collection::slice
     * @covers \Ghostwriter\Collection\Collection::take
     * @covers \Ghostwriter\Collection\Collection::toArray
     */
    public function testDropTakeSlice(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertCount(3, $collection);

        $new = $collection->append([4, 5, 6, 7, 8, 9])
            ->map(static fn (int $v): int => $v * 10)
            ->filter(static fn (int $v): bool => 0 === $v % 20)
            ->slice(1, 3)
            ->drop(1)
            ->take(1);

        // print_r([$new]);
        self::assertNull($new->first(static fn (mixed $value): bool => is_object($value)));
        self::assertSame(60, $new->first());
        self::assertSame([60], $new->toArray());
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::count
     * @covers \Ghostwriter\Collection\Collection::fromGenerator
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::isEmpty
     */
    public function testFromGenerator(): void
    {
        $generator = static fn (): Generator => yield from [];
        $collection = Collection::fromGenerator($generator);
        self::assertEmpty($collection);
        self::assertTrue($collection->isEmpty());
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::count
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     */
    public function testFromIterable(): void
    {
        self::assertEmpty(Collection::fromIterable());
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::last
     */
    public function testLast(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertSame(3, $collection->last());
        self::assertSame(2, $collection->last(static fn (int $value): bool => 0 === $value % 2));
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::reduce
     */
    public function testReduce(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertSame(6, $collection->reduce(
            static fn (?int $accumulator, int $value): int => ($accumulator ?? 0) + $value
        ));

        self::assertSame('123', $collection->reduce(
            static fn (?string $accumulator, int $value): string => ($accumulator ?? '') . $value
        ));

        self::assertNull($collection->reduce(
            static fn (mixed $accumulator, int $_): ?string => null === $accumulator ? $accumulator : null
        ));
    }

    /**
     * @covers \Ghostwriter\Collection\Collection::__construct
     * @covers \Ghostwriter\Collection\Collection::fromIterable
     * @covers \Ghostwriter\Collection\Collection::getIterator
     * @covers \Ghostwriter\Collection\Collection::slice
     * @covers \Ghostwriter\Collection\Collection::toArray
     *
     * @dataProvider sliceDataProvider
     *
     * @param array<int<0,max>> $slice
     */
    public function testSlice(iterable $input, array $slice, array $expected, bool $throws = false): void
    {
        $collection = Collection::fromIterable($input);
        if ($throws) {
            $this->expectException(InvalidArgumentException::class);
        }
        self::assertSame($expected, $collection->slice(...$slice)->toArray());
    }
}

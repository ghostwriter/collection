<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Tests\Unit;

use Generator;
use Ghostwriter\Collection\Collection;
use Ghostwriter\Collection\Exception\CollectionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function sprintf;

#[CoversClass(Collection::class)]
final class CollectionTest extends TestCase
{
    public static function sliceDataProvider(): Generator
    {
        yield from [
            'empty' => [[], [0], []],
            '[1,2,3] -> slice(0, -1)' => [[1, 2, 3], [0, -1], [1], true],
            '[1,2,3] -> slice(-1)' => [[1, 2, 3], [-1], [1], true],
            '[1,2,3] -> slice(0)' => [[1, 2, 3], [0], [1, 2, 3]],
            '[1,2,3] -> slice(0, 1)' => [[1, 2, 3], [0, 1], [1]],
            '[1,2,3] -> slice(1, 1)' => [[1, 2, 3], [1, 1], [2]],
            '[1,2,3] -> slice(1, PHP_MAX_INT)' => [[1, 2, 3], [1], [2, 3]],
            '[1,2,3] -> slice(1, 0)' => [[1, 2, 3], [1, 0], []],
        ];
    }

    public function testContains(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);

        self::assertTrue($collection->contains(2));

        self::assertTrue($collection->contains(static fn (int $value, int $key): bool => $value === 3 && $key === 2));

        self::assertTrue($collection->contains(static fn (int $value): bool => $value === 1));

        self::assertFalse(
            $collection->contains(static fn (int $value, int $key): bool => $value === 0 && $key === 1)
        );
    }

    public function testDropTakeSlice(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertCount(3, $collection);

        $collection = $collection->append([4, 5, 6, 7, 8, 9])
            ->map(static fn (int $v): int => $v * 10)
            ->filter(static fn (int $v): bool => $v % 20 === 0)
            ->slice(1, 3)
            ->drop(1)
            ->take(1);

        self::assertNull($collection->first(static fn (mixed $value): bool => is_object($value)));
        self::assertSame(60, $collection->first());
        self::assertSame([60], $collection->toArray());
    }

    public function testFromGenerator(): void
    {
        self::assertCount(0, Collection::fromGenerator(static fn (): Generator => yield from []));
    }

    public function testFromIterable(): void
    {
        self::assertEmpty(Collection::fromIterable([]));
    }

    public function testLast(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertSame(3, $collection->last());
        self::assertSame(2, $collection->last(static fn (int $value): bool => $value % 2 === 0));
    }

    public function testReadMeExample(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertCount(3, $collection);

        $collection = $collection->append([4, 5, 6, 7, 8, 9])
            ->map(static fn (int $v): int => $v * 10)
            ->filter(static fn (int $v): bool => $v % 20 === 0);

        self::assertSame([20, 40, 60, 80], $collection->toArray());
        self::assertSame([60], $collection->drop(1)->take(2)->slice(1, 1)->toArray());
    }

    public function testReduce(): void
    {
        $collection = Collection::fromIterable([1, 2, 3]);
        self::assertSame(6, $collection->reduce(
            static fn (mixed $accumulator, int $value): int =>
            /** @var null|int $accumulator */
            $accumulator !== null ? $accumulator + $value : $value
        ));

        self::assertSame('123', $collection->reduce(
            static fn (mixed $accumulator, int $value): string => is_string($accumulator) ? sprintf(
                '%s%s',
                $accumulator,
                $value
            ) : (string) $value
        ));

        self::assertNull($collection->reduce(
            static fn (mixed $accumulator, int $_): ?string =>
            /** @var null|string $accumulator */
            $accumulator
        ));
    }

    /**
     * @param array<int<0,max>> $slice
     */
    #[DataProvider('sliceDataProvider')]
    public function testSlice(array $input, array $slice, array $expected, bool $throws = false): void
    {
        $collection = Collection::fromIterable($input);
        if ($throws) {
            $this->expectException(CollectionException::class);
        }

        self::assertSame($expected, $collection->slice(...$slice)->toArray());
    }
}

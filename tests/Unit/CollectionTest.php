<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\Collection\Collection;
use Ghostwriter\Collection\Exception\FirstValueNotFoundException;
use Ghostwriter\Collection\Exception\LengthMustBePositiveIntegerException;
use Ghostwriter\Collection\Exception\OffsetMustBePositiveIntegerException;
use Ghostwriter\Collection\Interface\ExceptionInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

use function array_sum;
use function is_int;
use function is_object;
use function is_string;
use function sprintf;

#[CoversClass(Collection::class)]
final class CollectionTest extends TestCase
{
    public function testAppendNothing(): void
    {
        $collection = Collection::new([1, 2, 3]);
        self::assertSame($collection, $collection->append([]));
        self::assertNotSame($collection, $collection->append([4]));
    }

    public function testContains(): void
    {
        $collection = Collection::new([1, 2, 3]);

        self::assertTrue($collection->contains(2));

        self::assertTrue($collection->contains(static fn (int $value): bool => $value === 3));

        self::assertTrue($collection->contains(static fn (int $value): bool => $value === 1));

        self::assertFalse($collection->contains(static fn (int $value): bool => $value === 0));

        self::assertSame(3, $collection->count());
    }

    public function testDropTakeSlice(): void
    {
        $collection = Collection::new([1, 2, 3]);
        self::assertCount(3, $collection);

        $collection = $collection->append([4, 5, 6, 7, 8, 9])
            ->map(static fn (int $v): int => $v * 10)
            ->filter(static fn (int $v): bool => $v % 20 === 0)
            ->slice(1, 3)
            ->drop(1)
            ->take(1);

        self::assertSame(60, $collection->first());
        self::assertSame([60], $collection->toArray());

        $this->expectException(FirstValueNotFoundException::class);
        self::assertNull($collection->first(static fn (mixed $value): bool => is_object($value)));
    }

    public function testEach(): void
    {
        $counter = new class() {
            public int $value = 0;

            public function increment(int $value): int
            {
                return $this->value += $value;
            }

            public function count(): int
            {
                return $this->value;
            }
        };

        $expected = [1, 2, 3];

        $collection = Collection::new($expected);

        $collection->each(static fn (mixed $value) => $counter->increment($value));

        self::assertSame(array_sum($expected), $counter->count());
    }

    public function testFromGenerator(): void
    {
        self::assertCount(0, Collection::from(static fn (): Generator => yield from []));
    }

    public function testFromIterable(): void
    {
        self::assertEmpty(Collection::new([]));
    }

    public function testLast(): void
    {
        $collection = Collection::new([1, 2, 3]);

        self::assertSame(3, $collection->last());

        self::assertSame(
            2,
            $collection->last(static fn (mixed $value): bool => is_int($value) && $value % 2 === 0)
        );
    }

    public function testReadMeExample(): void
    {
        $collection = Collection::new([1, 2, 3]);
        self::assertSame(3, $collection->count());

        $collection = $collection->append([4, 5, 6, 7, 8, 9])
            ->map(static fn (int $v): int => $v * 10)
            ->filter(static fn (int $v): bool => $v % 20 === 0);

        self::assertSame([20, 40, 60, 80], $collection->toArray());
        self::assertSame([60], $collection->drop(1)->take(2)->slice(1, 1)->toArray());
    }

    public function testReduce(): void
    {
        $collection = Collection::new([1, 2, 3]);

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
            ) : $value . ''
        ));

        self::assertNull($collection->reduce(
            static fn (mixed $accumulator, int $_): ?string =>
            /** @var null|string $accumulator */
            $accumulator
        ));
    }

    /**
     * @param array<int<0,max>>        $slice
     * @param array<int,int>           $input
     * @param array<int,int>           $expected
     * @param ?class-string<Throwable> $throws
     */
    #[DataProvider('sliceDataProvider')]
    public function testSlice(array $input, array $slice, array $expected, string $throws = null): void
    {
        $collection = Collection::new($input);

        if (is_string($throws)) {
            $this->expectException(ExceptionInterface::class);
            $this->expectException($throws);
        }

        self::assertSame($expected, $collection->slice(...$slice)->toArray());
    }

    public static function sliceDataProvider(): Generator
    {
        yield from [
            'empty' => [[], [0], []],
            '[1,2,3] -> slice(0, -1)' => [[1, 2, 3], [0, -1], [1], LengthMustBePositiveIntegerException::class],
            '[1,2,3] -> slice(-1)' => [[1, 2, 3], [-1], [1], OffsetMustBePositiveIntegerException::class],
            '[1,2,3] -> slice(0)' => [[1, 2, 3], [0], [1, 2, 3]],
            '[1,2,3] -> slice(0, 1)' => [[1, 2, 3], [0, 1], [1]],
            '[1,2,3] -> slice(1, 1)' => [[1, 2, 3], [1, 1], [2]],
            '[1,2,3] -> slice(1, PHP_MAX_INT)' => [[1, 2, 3], [1], [2, 3]],
            '[1,2,3] -> slice(1, 0)' => [[1, 2, 3], [1, 0], []],
        ];
    }
}

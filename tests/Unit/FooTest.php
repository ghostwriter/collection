<?php

declare(strict_types=1);

namespace Ghostwriter\wip\Tests\Unit;

use Ghostwriter\wip\Foo;

/**
 * @coversDefaultClass \Ghostwriter\wip\Foo
 *
 * @internal
 *
 * @small
 */
final class FooTest extends AbstractTestCase
{
    /** @covers ::test */
    public function test(): void
    {
        self::assertTrue((new Foo())->test());
    }
}

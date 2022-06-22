<?php

declare(strict_types=1);

namespace Ghostwriter\Collection;

use Ghostwriter\Collection\Contract\CollectionInterface;

/**
 * @template TValue
 * @extends AbstractCollection<TValue>
 * @implements CollectionInterface<TValue>
 */
final class Collection extends AbstractCollection implements CollectionInterface
{
}

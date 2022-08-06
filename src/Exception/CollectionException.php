<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Exception;

use Ghostwriter\Collection\Contract\CollectionExceptionInterface;
use RuntimeException;

final class CollectionException extends RuntimeException implements CollectionExceptionInterface
{
}

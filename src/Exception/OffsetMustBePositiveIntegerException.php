<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Exception;

use Ghostwriter\Collection\ExceptionInterface;

final class OffsetMustBePositiveIntegerException extends \InvalidArgumentException implements ExceptionInterface
{
}

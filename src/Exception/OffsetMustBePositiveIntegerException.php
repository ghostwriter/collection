<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Exception;

use Ghostwriter\Collection\Interface\ExceptionInterface;
use InvalidArgumentException;

final class OffsetMustBePositiveIntegerException extends InvalidArgumentException implements ExceptionInterface {}

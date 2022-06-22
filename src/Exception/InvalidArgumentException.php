<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Exception;

use Ghostwriter\Collection\Contract\CollectionExceptionInterface;
use InvalidArgumentException as PHPInvalidArgumentException;

final class InvalidArgumentException extends PHPInvalidArgumentException implements CollectionExceptionInterface
{
}

<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Exception;

use Ghostwriter\Collection\Interface\ExceptionInterface;
use RuntimeException;

final class FirstValueNotFoundException extends RuntimeException implements ExceptionInterface
{
}

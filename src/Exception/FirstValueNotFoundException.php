<?php

declare(strict_types=1);

namespace Ghostwriter\Collection\Exception;

use Ghostwriter\Collection\ExceptionInterface;

final class FirstValueNotFoundException extends \RuntimeException implements ExceptionInterface
{
}

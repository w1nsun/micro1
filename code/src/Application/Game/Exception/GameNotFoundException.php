<?php

declare(strict_types=1);

namespace App\Application\Game\Exception;

class GameNotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}

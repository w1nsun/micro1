<?php

declare(strict_types=1);

namespace App\Application\Game\Exception;

class CategoryNotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}

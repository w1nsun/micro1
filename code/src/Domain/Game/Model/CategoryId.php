<?php

declare(strict_types=1);

namespace App\Domain\Game\Model;

use App\Domain\Shared\Model\Identity;

class CategoryId extends Identity
{
    public function value(): int
    {
        return $this->id;
    }
}

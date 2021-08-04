<?php

declare(strict_types=1);

namespace App\Domain\Game\Model;

use App\Domain\Shared\Model\Identity;

class GameId extends Identity
{
    public function value(): string
    {
        return $this->id;
    }
}

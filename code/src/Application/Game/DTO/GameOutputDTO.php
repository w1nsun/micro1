<?php

declare(strict_types=1);

namespace App\Application\Game\DTO;

use App\Domain\Game\Model\Game;

class GameOutputDTO implements \JsonSerializable
{
    private Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function jsonSerialize()
    {
        return $this->game->toArray();
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetGame;

use Symfony\Component\Validator\Constraints as Assert;

class GetGameCommand
{
    /**
     * @Assert\NotBlank
     */
    public string $gameId;

    public function __construct(string $gameId)
    {
        $this->gameId = $gameId;
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetGame;

use App\Application\Game\DTO\GameOutputDTO;
use App\Application\Game\Exception\GameNotFoundException;
use App\Domain\Game\Model\GameId;
use App\Domain\Game\Repository\GameRepository;

class GetGameCommandHandler
{
    private GameRepository $gameRepo;

    public function __construct(GameRepository $gameRepo)
    {
        $this->gameRepo = $gameRepo;
    }

    public function handle(GetGameCommand $command): GameOutputDTO
    {
        $gameId = new GameId($command->getGameId());
        $game = $this->gameRepo->findOneById($gameId);

        if (!$game) {
            throw new GameNotFoundException(sprintf('Game "%s" not found.', $gameId));
        }

        return new GameOutputDTO($game);
    }
}

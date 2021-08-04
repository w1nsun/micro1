<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetGame;

use App\Application\Game\DTO\GameListOutputDTO;
use App\Application\Game\DTO\GameOutputDTO;
use App\Application\Game\Exception\CategoryNotFoundException;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\GameRepository;
use App\Domain\Game\Repository\CategoryRepository;

class GetCategoryGamesCommandHandler
{
    private CategoryRepository $categoryRepo;
    private GameRepository $gameRepo;

    public function __construct(CategoryRepository $categoryRepo, GameRepository $gameRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->gameRepo = $gameRepo;
    }

    public function handle(GetCategoryGamesCommand $command): GameListOutputDTO
    {
        $categoryId = new CategoryId($command->getCategoryId());
        $category = $this->categoryRepo->findOneById($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException(sprintf('Category "%s" not found.', $categoryId));
        }

        $games = $this->gameRepo->findByCategoryId($categoryId, $command->getOffset(), $command->getLimit());
        $total = $this->gameRepo->getCountByCategoryId($categoryId);

        $gamesDTO = [];
        foreach ($games as $game) {
            $gamesDTO[] = new GameOutputDTO($game);
        }

        return new GameListOutputDTO($gamesDTO, $total);
    }
}

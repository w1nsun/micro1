<?php

declare(strict_types=1);

namespace App\Application\Game\Command\DeleteGame;

use App\Application\Game\Exception\CategoryNotFoundException;
use App\Domain\Game\Model\GameId;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\GameRepository;
use App\Domain\Game\Repository\CategoryRepository;

class DeleteGamesCommandHandler
{
    private CategoryRepository $categoryRepo;
    private GameRepository $gameRepo;

    public function __construct(CategoryRepository $categoryRepo, GameRepository $gameRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->gameRepo = $gameRepo;
    }

    public function handle(DeleteGamesCommand $command): void
    {
        $categoryId = new CategoryId($command->getCategoryId());

        if (!$this->categoryRepo->existsById($categoryId)) {
            throw new CategoryNotFoundException(sprintf('Category "%s" not found.', $command->getCategoryId()));
        }

        $gamesIds = $command->getGamesIds();
        array_walk($gamesIds, static function ($item) {
            return $item instanceof GameId ? $item : new GameId($item);
        });

        $this->gameRepo->deleteByCategoryIdAndIds($categoryId, $gamesIds);
    }
}

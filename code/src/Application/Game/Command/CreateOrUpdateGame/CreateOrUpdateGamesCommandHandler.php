<?php

declare(strict_types=1);

namespace App\Application\Game\Command\CreateOrUpdateGame;

use App\Application\Game\DTO\GameListOutputDTO;
use App\Application\Game\DTO\GameOutputDTO;
use App\Application\Game\Exception\CategoryNotFoundException;
use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\GameId;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\GameRepository;
use App\Domain\Game\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;

class CreateOrUpdateGamesCommandHandler
{
    private CategoryRepository $categoryRepo;
    private GameRepository $gameRepo;
    private EntityManager $em;

    public function __construct(
        CategoryRepository $categoryRepo,
        GameRepository     $gameRepo,
        EntityManager      $em
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->gameRepo = $gameRepo;
        $this->em = $em;
    }

    public function handle(CreateOrUpdateGamesCommand $command): GameListOutputDTO
    {
        $categoryId = new CategoryId($command->getCategoryId());

        if (!$this->categoryRepo->existsById($categoryId)) {
            throw new CategoryNotFoundException(sprintf('Category "%s" not found.', $command->getCategoryId()));
        }

        $existingGames = $this->findExistingGames($command->getGamesIds());

        $gamesDTO = [];
        foreach ($command->getGames() as $gameInputDTO) {
            $gameId = new GameId($gameInputDTO->getGameId());

            if (isset($existingGames[$gameInputDTO->getGameId()])) {
                $game = $existingGames[$gameInputDTO->getGameId()];
                $game->setCategoryId($categoryId);
            } else {
                $game = new Game($gameId, $categoryId);
                $this->em->persist($game);
            }

            $game
                ->setDesktop($gameInputDTO->getDesktop()->toValueObject())
                ->setMobile($gameInputDTO->getMobile()->toValueObject())
                ->setIOS($gameInputDTO->getIOS()->toValueObject())
                ->setAndroid($gameInputDTO->getAndroid()->toValueObject())
                ->setExtraData($gameInputDTO->getExtraData())
            ;

            $gamesDTO[] = new GameOutputDTO($game);
        }

        $this->em->flush();

        $total = $this->gameRepo->getCountByCategoryId($categoryId);

        return new GameListOutputDTO($gamesDTO, $total);
    }

    /**
     * @param string[] $gamesIds
     *
     * @return Game[]
     */
    private function findExistingGames(array $gamesIds): array
    {
        array_walk($gamesIds, static function ($item) {
            return $item instanceof GameId ? $item : new GameId($item);
        });

        return $this->gameRepo->findByIds($gamesIds);
    }
}

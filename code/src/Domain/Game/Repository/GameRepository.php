<?php

declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\GameId;
use App\Domain\Game\Model\CategoryId;

interface GameRepository
{
    public function findOneById(GameId $gameId): ?Game;

    /**
     * @param GameId[] $ids
     *
     * @return Game[]
     */
    public function findByIds(array $ids): iterable;

    /**
     * @return int Number of deleted items
     */
    public function deleteByCategoryIdAndIds(CategoryId $categoryId, array $ids): int;

    public function deleteByCategoryId(CategoryId $categoryId): int;

    /**
     * @return Game[]|iterable
     */
    public function findByCategoryId(CategoryId $categoryId, int $offset, int $limit): iterable;

    public function getCountByCategoryId(CategoryId $categoryId): int;
}

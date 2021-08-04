<?php

declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Model\Category;
use App\Domain\Game\Model\CategoryId;

interface CategoryRepository
{
    public function findOneById(CategoryId $categoryId): ?Category;

    public function existsById(CategoryId $categoryId): bool;

    /**
     * @return iterable|Category[]
     */
    public function findWithLimits(int $offset, int $limit): iterable;

    public function getCount(): int;
}

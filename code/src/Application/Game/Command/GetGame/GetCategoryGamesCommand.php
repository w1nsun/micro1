<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetGame;

class GetCategoryGamesCommand
{
    private int $categoryId;
    private int $offset;
    private int $limit;

    public function __construct(int $categoryId, int $offset, int $limit)
    {
        $this->categoryId = $categoryId;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}

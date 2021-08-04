<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetCategory;

class GetCategoryCommand
{
    private int $categoryId;

    public function __construct(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
}

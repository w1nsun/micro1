<?php

declare(strict_types=1);

namespace App\Application\Game\DTO;

class CategoryListOutputDTO
{
    /**
     * @var CategoryOutputDTO[]
     */
    private array $categories;

    private int $total;

    /**
     * @param CategoryOutputDTO[] $categories
     */
    public function __construct(array $categories, int $total)
    {
        $this->categories = $categories;
        $this->total = $total;
    }

    /**
     * @return CategoryOutputDTO[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}

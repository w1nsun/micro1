<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetCategory;

use App\Application\Game\DTO\CategoryListOutputDTO;
use App\Application\Game\DTO\CategoryOutputDTO;
use App\Domain\Game\Repository\CategoryRepository;

class GetCategoryListCommandHandler
{
    private CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function handle(GetCategoryListCommand $command): CategoryListOutputDTO
    {
        $categories = $this->categoryRepo->findWithLimits($command->getOffset(), $command->getLimit());
        $totalCount = $this->categoryRepo->getCount();

        $categoriesDTO = [];
        foreach ($categories as $category) {
            $categoriesDTO[] = new CategoryOutputDTO($category);
        }

        return new CategoryListOutputDTO($categoriesDTO, $totalCount);
    }
}

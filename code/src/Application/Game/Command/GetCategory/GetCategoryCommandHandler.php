<?php

declare(strict_types=1);

namespace App\Application\Game\Command\GetCategory;

use App\Application\Game\DTO\CategoryOutputDTO;
use App\Application\Game\Exception\CategoryNotFoundException;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\CategoryRepository;

class GetCategoryCommandHandler
{
    private CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function handle(GetCategoryCommand $command): CategoryOutputDTO
    {
        $categoryId = new CategoryId($command->getCategoryId());
        $category = $this->categoryRepo->findOneById($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException(sprintf('Category "%s" not found.', $categoryId));
        }

        return new CategoryOutputDTO($category);
    }
}

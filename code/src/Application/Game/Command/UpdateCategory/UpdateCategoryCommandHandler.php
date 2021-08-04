<?php

declare(strict_types=1);

namespace App\Application\Game\Command\UpdateCategory;

use App\Application\Game\Command\CreateOrUpdateCategoryCommand;
use App\Application\Game\DTO\CategoryOutputDTO;
use App\Application\Game\Exception\CategoryNotFoundException;
use App\Domain\Game\DTO\ConfiguratorDTO;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;

class UpdateCategoryCommandHandler
{
    private EntityManager $em;
    private CategoryRepository $categoryRepo;

    public function __construct(EntityManager $em, CategoryRepository $categoryRepo)
    {
        $this->em = $em;
        $this->categoryRepo = $categoryRepo;
    }

    public function handle(CreateOrUpdateCategoryCommand $command): CategoryOutputDTO
    {
        $categoryId = new CategoryId($command->getCategoryId());
        $category = $this->categoryRepo->findOneById($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException(sprintf('Category "%s" not found.', $categoryId));
        }

        $category
            ->setName($command->getName())
            ->setConfigurator(new ConfiguratorDTO($command->getConfigurator()))
            ->setCurrencies($command->getCurrencies())
        ;

        $this->em->flush();

        return new CategoryOutputDTO($category);
    }
}

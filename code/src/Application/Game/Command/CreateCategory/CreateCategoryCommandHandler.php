<?php

declare(strict_types=1);

namespace App\Application\Game\Command\CreateCategory;

use App\Application\Game\Command\CreateOrUpdateCategoryCommand;
use App\Application\Game\DTO\CategoryOutputDTO;
use App\Domain\Game\DTO\ConfiguratorDTO;
use App\Domain\Game\Model\Category;
use App\Domain\Game\Model\CategoryId;
use Doctrine\ORM\EntityManager;

class CreateCategoryCommandHandler
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle(CreateOrUpdateCategoryCommand $command): CategoryOutputDTO
    {
        $category = new Category(
            new CategoryId($command->getCategoryId()),
            $command->getName(),
            new ConfiguratorDTO($command->getConfigurator())
        );

        $category
            ->setCurrencies($command->getCurrencies())
        ;

        $this->em->persist($category);
        $this->em->flush();

        return new CategoryOutputDTO($category);
    }
}

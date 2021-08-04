<?php

declare(strict_types=1);

namespace App\Tests\_helpers;

use App\Domain\Game\DTO\ConfiguratorDTO;
use App\Domain\Game\Model\Category;
use App\Domain\Game\Model\CategoryId;
use Doctrine\ORM\EntityManager;

class CategoryFixtures
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createCategory(int $id = 1): Category
    {
        $category = new Category(
            new CategoryId($id),
            'TestName',
            new ConfiguratorDTO()
        );

        $category->setCurrencies(['EUR' => 'EUR', 'USD' => 'USD']);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }
}

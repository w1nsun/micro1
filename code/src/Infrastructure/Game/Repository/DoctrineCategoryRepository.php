<?php

declare(strict_types=1);

namespace App\Infrastructure\Game\Repository;

use App\Domain\Game\Model\Category;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\CategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCategoryRepository extends ServiceEntityRepository implements CategoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findOneById(CategoryId $categoryId): ?Category
    {
        return $this->find($categoryId);
    }

    public function existsById(CategoryId $categoryId): bool
    {
        return 0 < $this->createQueryBuilder('category')
            ->select('count(category.id)')
            ->andWhere('category.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return iterable|Category[]
     */
    public function findWithLimits(int $offset, int $limit): iterable
    {
        return $this->createQueryBuilder('p')
            ->select()
            ->orderBy('p.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCount(): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}

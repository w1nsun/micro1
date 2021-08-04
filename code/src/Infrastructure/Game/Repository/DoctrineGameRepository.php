<?php

declare(strict_types=1);

namespace App\Infrastructure\Game\Repository;

use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\GameId;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\GameRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGameRepository extends ServiceEntityRepository implements GameRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findOneById(GameId $gameId): ?Game
    {
        return $this->find($gameId);
    }

    public function findByIds(array $ids): iterable
    {
        return $this->createQueryBuilder('g')
            ->select()
            ->andWhere('g.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->indexBy('g', 'g.id')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int Number of deleted items
     */
    public function deleteByCategoryIdAndIds(CategoryId $categoryId, array $ids): int
    {
        return $this->createQueryBuilder('g')
            ->delete()
            ->where('g.id IN (:ids) AND g.categoryId = :categoryId')
            ->setParameter('ids', $ids)
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->execute()
        ;
    }

    public function deleteByCategoryId(CategoryId $categoryId): int
    {
        return $this->createQueryBuilder('g')
            ->delete()
            ->where('g.categoryId = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->execute()
        ;
    }

    public function findByCategoryId(CategoryId $categoryId, int $offset = 0, int $limit = 50): iterable
    {
        return $this->createQueryBuilder('g')
            ->select()
            ->orderBy('g.id', 'ASC')
            ->where('g.categoryId = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCountByCategoryId(CategoryId $categoryId): int
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->where('g.categoryId = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\_helpers;

use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\GameId;
use App\Domain\Game\Model\CategoryId;
use Doctrine\ORM\EntityManager;

class GameFixtures
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createGame(string $id, int $categoryId): Game
    {
        $game = new Game(new GameId($id), new CategoryId($categoryId));

        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }
}

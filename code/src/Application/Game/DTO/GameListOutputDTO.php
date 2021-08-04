<?php

declare(strict_types=1);

namespace App\Application\Game\DTO;

class GameListOutputDTO
{
    /**
     * @var GameOutputDTO[]
     */
    private array $games;

    private int $total;

    /**
     * @param GameOutputDTO[] $games
     * @param int             $total Total number of records in DB
     */
    public function __construct(array $games, int $total)
    {
        $this->games = $games;
        $this->total = $total;
    }

    public function getGames(): array
    {
        return $this->games;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}

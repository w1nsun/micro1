<?php

declare(strict_types=1);

namespace App\Application\Game\Command\DeleteGame;

use App\Application\Game\Validator\GameId as AssertGameId;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteGamesCommand
{
    /**
     * @Assert\NotBlank
     */
    private int $categoryId;

    /**
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @AssertGameId
     * })
     */
    private array $games;

    public function __construct(array $games)
    {
        $this->games = $games;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return string[]
     */
    public function getGamesIds(): array
    {
        $ids = [];
        foreach ($this->games as $game) {
            $ids[] = $game['game_id'];
        }

        return $ids;
    }
}

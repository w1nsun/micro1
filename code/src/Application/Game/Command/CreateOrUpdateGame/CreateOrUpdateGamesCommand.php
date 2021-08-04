<?php

declare(strict_types=1);

namespace App\Application\Game\Command\CreateOrUpdateGame;

use App\Application\Game\DTO\GameInputDTO;
use App\Application\Game\Validator\GameId as AssertGameId;
use App\Application\Game\Validator\PlatformEnabledCategoryGame as AssertPlatformEnabledCategoryGame;
use App\Application\Game\Validator\PlatformNotBlank as AssertPlatformNotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrUpdateGamesCommand
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    private $categoryId;

    /**
     * @Assert\Sequentially({
     *     @Assert\Type("array"),
     *     @Assert\All({
     *         @Assert\NotBlank,
     *         @AssertPlatformNotBlank(
     *             requiredPlatforms={"desktop", "mobile", "ios", "android"},
     *             requiredProps={"category_game_id", "has_demo", "enabled"}
     *         ),
     *         @AssertGameId,
     *         @AssertPlatformEnabledCategoryGame
     *     })
     * })
     */
    private $games;

    public function __construct($categoryId, $games)
    {
        $this->categoryId = $categoryId;
        $this->games = $games;
    }

    public function getCategoryId(): int
    {
        return (int) $this->categoryId;
    }

    /**
     * @return GameInputDTO[]
     */
    public function getGames(): array
    {
        $gamesDTO = [];
        foreach ($this->games as $game) {
            $gamesDTO[] = new GameInputDTO($game);
        }

        return $gamesDTO;
    }

    /**
     * @return string[]
     */
    public function getGamesIds(): array
    {
        $ids = [];
        foreach ($this->getGames() as $inputGameDTO) {
            $ids[] = $inputGameDTO->getGameId();
        }

        return $ids;
    }
}

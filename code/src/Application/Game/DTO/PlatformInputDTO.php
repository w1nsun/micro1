<?php

declare(strict_types=1);

namespace App\Application\Game\DTO;

use App\Domain\Game\Model\Platform;

class PlatformInputDTO
{
    private ?string $categoryGameId;
    private bool $hasDemo;
    private bool $enabled;
    private ?array $extraData;

    public function __construct(array $data)
    {
        $this->categoryGameId = $data['category_game_id'] ?? null;
        $this->hasDemo = (bool) $data['has_demo'];
        $this->enabled = (bool) $data['enabled'];

        unset($data['category_game_id'], $data['has_demo'], $data['enabled']);
        $this->extraData = empty($data) ? null : $data;
    }

    public function getCategoryGameId(): ?string
    {
        return $this->categoryGameId;
    }

    public function isHasDemo(): bool
    {
        return $this->hasDemo;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

    public function toValueObject(): Platform
    {
        return (new Platform())
            ->setCategoryGameId($this->getCategoryGameId())
            ->setEnabled($this->isEnabled())
            ->setHasDemo($this->isHasDemo())
            ->setExtraData($this->getExtraData())
        ;
    }
}

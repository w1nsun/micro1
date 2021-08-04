<?php

declare(strict_types=1);

namespace App\Domain\Game\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Embeddable */
class Platform
{
    /**
     * @ORM\Column(type="string", nullable=true, length=255, name="category_game_id")
     */
    private ?string $categoryGameId;

    /**
     * @ORM\Column(type="boolean", name="has_demo")
     */
    private bool $hasDemo = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled = false;

    /**
     * @ORM\Column(type="json", nullable=true, name="extra_data", options={"jsonb": true})
     */
    private ?array $extraData;

    public function getCategoryGameId(): ?string
    {
        return $this->categoryGameId;
    }

    public function setCategoryGameId(?string $categoryGameId): self
    {
        $this->categoryGameId = $categoryGameId;

        return $this;
    }

    public function isHasDemo(): bool
    {
        return $this->hasDemo;
    }

    public function setHasDemo(bool $hasDemo): self
    {
        $this->hasDemo = $hasDemo;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

    public function setExtraData(?array $extraData): self
    {
        $this->extraData = $extraData;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge([
            'category_game_id' => $this->getCategoryGameId(),
            'has_demo' => $this->isHasDemo(),
            'enabled' => $this->isEnabled(),
        ], $this->getExtraData() ?: []);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Game\DTO;

class GameInputDTO
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getGameId(): string
    {
        return $this->data['game_id'];
    }

    public function getDesktop(): PlatformInputDTO
    {
        return new PlatformInputDTO($this->data['platform']['desktop'] ?? []);
    }

    public function getMobile(): PlatformInputDTO
    {
        return new PlatformInputDTO($this->data['platform']['mobile'] ?? []);
    }

    public function getIOS(): PlatformInputDTO
    {
        return new PlatformInputDTO($this->data['platform']['ios'] ?? []);
    }

    public function getAndroid(): PlatformInputDTO
    {
        return new PlatformInputDTO($this->data['platform']['android'] ?? []);
    }

    public function getPlatform(string $platform): PlatformInputDTO
    {
        if ($this->data['platform'][$platform] || !\is_array($this->data['platform'][$platform])) {
            throw new \InvalidArgumentException(sprintf('Platform "%s" does not exists.', $platform));
        }

        return new PlatformInputDTO($this->data['platform'][$platform]);
    }

    public function getExtraData(): ?array
    {
        $data = $this->data;
        unset($data['game_id'], $data['platform'], $data['category_id']);

        return empty($data) ? null : $data;
    }
}

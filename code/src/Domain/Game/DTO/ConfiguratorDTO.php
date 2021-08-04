<?php

declare(strict_types=1);

namespace App\Domain\Game\DTO;

class ConfiguratorDTO
{
    private const GAME_SESSION_TTL = 120;
    private const GAME_SESSION_DB_LOG = false;

    private array $data = [
        'game_session' => [
            'ttl' => self::GAME_SESSION_TTL,
            'db_log' => self::GAME_SESSION_DB_LOG,
        ],
    ];

    public function __construct(array $data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Game\DoctrineCustomType;

use App\Domain\Game\Model\GameId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class GameIdType extends StringType
{
    public function getName(): string
    {
        return 'GameId';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return new GameId((string) $value);
    }

    /**
     * @param GameId $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof GameId) {
            return $value->value();
        }

        return (string) $value;
    }
}

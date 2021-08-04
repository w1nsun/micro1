<?php

declare(strict_types=1);

namespace App\Infrastructure\Game\DoctrineCustomType;

use App\Domain\Game\Model\CategoryId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class CategoryIdType extends IntegerType
{
    public function getName(): string
    {
        return 'CategoryId';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return new CategoryId((int) $value);
    }

    /**
     * @param CategoryId $value
     *
     * @return int
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof CategoryId) {
            return $value->value();
        }

        return (int) $value;
    }
}

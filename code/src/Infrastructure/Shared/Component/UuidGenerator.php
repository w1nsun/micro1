<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Component;

use Symfony\Component\Uid\Uuid;

class UuidGenerator
{
    public function v4(): string
    {
        if (class_exists('Symfony\Component\Uid\Uuid')) {
            return Uuid::v4()->toRfc4122();
        }

        throw new \RuntimeException('Please include Symfony\Component\Uid\Uuid component or some other');
    }
}

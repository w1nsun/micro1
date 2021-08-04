<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlatformNotBlank extends Constraint
{
    public string $message = 'Platform is required.';
    public string $messagePlatform = 'Platform "{{ platform }}" is required.';
    public string $messagePlatformProps = 'Property "{{ property }}" for "{{ platform }}" platform must be set.';
    public array $requiredPlatforms = [];
    public array $requiredProps = [];

    public function validatedBy(): string
    {
        return PlatformNotBlankValidator::class;
    }
}

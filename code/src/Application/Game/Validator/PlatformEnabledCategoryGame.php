<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlatformEnabledCategoryGame extends Constraint
{
    public string $message = 'You must specify game name for "{{ platform }}" platform.';

    public function validatedBy(): string
    {
        return PlatformEnabledCategoryGameValidator::class;
    }
}

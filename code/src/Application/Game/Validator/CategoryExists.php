<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CategoryExists extends Constraint
{
    public string $message = 'Category with ID "{{ CategoryID }}" already exists.';

    public function validatedBy(): string
    {
        return CategoryExistsValidator::class;
    }
}

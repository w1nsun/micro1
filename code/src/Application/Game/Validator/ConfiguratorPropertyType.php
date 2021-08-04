<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConfiguratorPropertyType extends Constraint
{
    public string $message = 'Property {{ property }} must be type "{{ type }}" but got {{ gotType }}.';

    public function validatedBy(): string
    {
        return ConfiguratorPropertyTypeValidator::class;
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Constraint;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationConverter
{
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    public function toArray(ConstraintViolationList $constraintViolationList): array
    {
        $errors = [];
        foreach ($constraintViolationList as $violation) {
            $propertyPath = new PropertyPath($violation->getPropertyPath());
            $path = '';
            foreach ($propertyPath->getElements() as $element) {
                $element = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $element));
                $path .= "[{$element}]";
            }

            $entryErrors = (array) $this->propertyAccessor->getValue($errors, $path);
            $entryErrors[] = $violation->getMessage();

            $this->propertyAccessor->setValue($errors, $path, $entryErrors);
        }

        return $errors;
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PlatformNotBlankValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PlatformNotBlank) {
            throw new UnexpectedTypeException($constraint, PlatformNotBlank::class);
        }

        if (!isset($value['platform'])) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

            return;
        }

        foreach ($constraint->requiredPlatforms as $requiredPlatform) {
            if (!isset($value['platform'][$requiredPlatform])) {
                $this->context->buildViolation($constraint->messagePlatform)
                    ->setParameter('{{ platform }}', $requiredPlatform)
                    ->addViolation();

                continue;
            }

            $platform = $value['platform'][$requiredPlatform];

            foreach ($constraint->requiredProps as $requiredProp) {
                if (!isset($platform[$requiredProp])) {
                    $this->context->buildViolation($constraint->messagePlatformProps)
                        ->setParameter('{{ platform }}', $requiredPlatform)
                        ->setParameter('{{ property }}', $requiredProp)
                        ->addViolation();
                }
            }
        }
    }
}

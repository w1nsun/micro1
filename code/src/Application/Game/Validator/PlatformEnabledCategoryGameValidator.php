<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PlatformEnabledCategoryGameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PlatformEnabledCategoryGame) {
            throw new UnexpectedTypeException($constraint, PlatformEnabledCategoryGame::class);
        }

        if (!isset($value['platform']) || !\is_array($value['platform'])) {
            return;
        }

        foreach ($value['platform'] as $platformName => $platform) {
            $isEnabled = isset($platform['enabled']) && $platform['enabled'];
            $hasCategoryGame = isset($platform['category_game_id']) && $platform['category_game_id'];

            if ($isEnabled && !$hasCategoryGame) {
                $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ platform }}', $platformName)
                        ->addViolation();
            }
        }
    }
}

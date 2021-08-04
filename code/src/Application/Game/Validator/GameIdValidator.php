<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class GameIdValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof GameId) {
            throw new UnexpectedTypeException($constraint, GameId::class);
        }

        if (!isset($value['game_id']) || empty($value['game_id'])) {
            $this->context->buildViolation($constraint->messageRequired)
                ->addViolation();

            return;
        }

        $gameIdLength = mb_strlen($value['game_id']);
        if ($gameIdLength < $constraint->min) {
            $this->context->buildViolation($constraint->minMessage)
                ->setParameter('{{ limit }}', $constraint->min)
                ->addViolation();
        }

        if ($gameIdLength > $constraint->max) {
            $this->context->buildViolation($constraint->maxMessage)
                ->setParameter('{{ limit }}', $constraint->max)
                ->addViolation();
        }
    }
}

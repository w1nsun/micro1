<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ConfiguratorPropertyTypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConfiguratorPropertyType) {
            throw new UnexpectedTypeException($constraint, ConfiguratorPropertyType::class);
        }

        if (isset($value['game_session']['ttl']) && !\is_int($value['game_session']['ttl'])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ property }}', 'game_session.ttl')
                ->setParameter('{{ type }}', 'int')
                ->setParameter('{{ gotType }}', \gettype($value['game_session']['ttl']))
                ->addViolation();
        }

        if (!isset($value['game_session']['db_log'])) {
            return;
        }

        $isBoolRepresentation = \is_int($value['game_session']['db_log']) || \is_bool($value['game_session']['db_log']);

        if (!$isBoolRepresentation) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ property }}', 'game_session.db_log')
                ->setParameter('{{ type }}', 'bool')
                ->setParameter('{{ gotType }}', \gettype($value['game_session']['db_log']))
                ->addViolation();
        }
    }
}

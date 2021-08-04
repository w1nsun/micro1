<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryExistsValidator extends ConstraintValidator
{
    private CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryExists) {
            throw new UnexpectedTypeException($constraint, CategoryExists::class);
        }

        if (!$value) {
            return;
        }

        $categoryId = new CategoryId($value);
        if ($this->categoryRepo->existsById($categoryId)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ CategoryID }}', (string) $categoryId)
                ->addViolation();
        }
    }
}

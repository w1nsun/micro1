<?php

declare(strict_types=1);

namespace App\Application\Game\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GameId extends Constraint
{
    public string $messageRequired = 'Property "game_id" must be set.';
    public $maxMessage = 'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.';
    public $minMessage = 'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.';
    public int $min = 2;
    public int $max = 255;

    public function validatedBy(): string
    {
        return GameIdValidator::class;
    }
}

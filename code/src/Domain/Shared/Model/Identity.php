<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

abstract class Identity
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    abstract public function value();

    public function __toString(): string
    {
        return (string) $this->value();
    }

    public function equals($identity): bool
    {
        return $identity instanceof static && $identity->value() === $this->value();
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Game\DTO;

use App\Domain\Game\Model\Category;

class CategoryOutputDTO implements \JsonSerializable
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategoryId(): int
    {
        return $this->category->getId()->value();
    }

    public function getName(): string
    {
        return $this->category->getName();
    }

    public function getConfigurator(): array
    {
        return $this->category->getConfigurator()->toArray();
    }

    public function getCurrencies(): array
    {
        return $this->category->getCurrencies();
    }

    public function jsonSerialize(): array
    {
        $configurator = $this->getConfigurator();
        $currencies = $this->getCurrencies();

        return [
            'category_id' => $this->getCategoryId(),
            'name' => $this->getName(),
            'configurator' => !empty($configurator) ? $configurator : new \stdClass(),
            'currencies' => !empty($currencies) ? $currencies : new \stdClass(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Game\Event;

use App\Domain\Game\Model\Category;
use App\Domain\Game\Model\CategoryId;
use Symfony\Contracts\EventDispatcher\Event;

class CategoryDeletedEvent extends Event
{
    public const NAME = 'game.category.deleted';

    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->category->getId();
    }
}

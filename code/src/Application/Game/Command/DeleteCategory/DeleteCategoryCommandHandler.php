<?php

declare(strict_types=1);

namespace App\Application\Game\Command\DeleteCategory;

use App\Application\Game\Event\CategoryDeletedEvent;
use App\Application\Game\Exception\CategoryNotFoundException;
use App\Domain\Game\Model\CategoryId;
use App\Domain\Game\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DeleteCategoryCommandHandler
{
    private EntityManager $em;
    private CategoryRepository $categoryRepo;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntityManager            $em,
        CategoryRepository       $categoryRepo,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->em = $em;
        $this->categoryRepo = $categoryRepo;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(DeleteCategoryCommand $command): void
    {
        $categoryId = new CategoryId($command->getId());
        $category = $this->categoryRepo->findOneById($categoryId);

        if (!$category) {
            throw new CategoryNotFoundException(sprintf('Category "%s" not found.', $categoryId));
        }

        $this->em->remove($category);

        $this->eventDispatcher->dispatch(new CategoryDeletedEvent($category), CategoryDeletedEvent::NAME);

        $this->em->flush();
    }
}

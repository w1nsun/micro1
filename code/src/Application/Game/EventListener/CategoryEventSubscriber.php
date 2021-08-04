<?php

declare(strict_types=1);

namespace App\Application\Game\EventListener;

use App\Application\Game\Event\CategoryDeletedEvent;
use App\Domain\Game\Repository\GameRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CategoryEventSubscriber implements EventSubscriberInterface
{
    private GameRepository $gameRepo;

    public function __construct(GameRepository $gameRepo)
    {
        $this->gameRepo = $gameRepo;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CategoryDeletedEvent::NAME => [
                ['deleteRelatedGames', 0],
            ],
        ];
    }

    public function deleteRelatedGames(CategoryDeletedEvent $event): void
    {
        $this->gameRepo->deleteByCategoryId($event->getCategoryId());
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\DeleteCategory\DeleteCategoryCommand;
use App\Application\Game\Command\DeleteCategory\DeleteCategoryCommandHandler;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteCategoryAction
{
    private DeleteCategoryCommandHandler $handler;

    public function __construct(DeleteCategoryCommandHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(Request $request, int $categoryId): Response
    {
        $command = new DeleteCategoryCommand($categoryId);
        $this->handler->handle($command);

        return new SuccessJsonResponse([
            'message' => sprintf('Category "%s" successfully deleted', $categoryId),
        ]);
    }
}

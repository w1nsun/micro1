<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\GetCategory\GetCategoryCommand;
use App\Application\Game\Command\GetCategory\GetCategoryCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCategoryAction
{
    private ApiSerializer $serializer;
    private GetCategoryCommandHandler $handler;

    public function __construct(
        ApiSerializer $serializer,
        GetCategoryCommandHandler $handler
    ) {
        $this->serializer = $serializer;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, int $categoryId): Response
    {
        $command = new GetCategoryCommand($categoryId);
        $categoryOutputDTO = $this->handler->handle($command);

        $data = $this->serializer->normalize($categoryOutputDTO, 'json');

        return new SuccessJsonResponse(['data' => $data], Response::HTTP_OK);
    }
}

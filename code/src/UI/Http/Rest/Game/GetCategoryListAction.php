<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\GetCategory\GetCategoryListCommand;
use App\Application\Game\Command\GetCategory\GetCategoryListCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCategoryListAction
{
    private ApiSerializer $serializer;
    private GetCategoryListCommandHandler $handler;

    public function __construct(
        ApiSerializer $serializer,
        GetCategoryListCommandHandler $handler
    ) {
        $this->serializer = $serializer;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        $command = new GetCategoryListCommand(
            (int) $request->get('offset', 0),
            (int) $request->get('limit', 50)
        );

        $categoryListOutputDTO = $this->handler->handle($command);

        $data = $this->serializer->normalize($categoryListOutputDTO->getCategories(), 'json');
        $meta = [
            'total' => $categoryListOutputDTO->getTotal(),
        ];

        return new SuccessJsonResponse(['data' => $data, 'meta' => $meta], Response::HTTP_OK);
    }
}

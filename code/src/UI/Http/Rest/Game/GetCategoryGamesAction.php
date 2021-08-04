<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\GetGame\GetCategoryGamesCommand;
use App\Application\Game\Command\GetGame\GetCategoryGamesCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCategoryGamesAction
{
    private ApiSerializer $serializer;
    private GetCategoryGamesCommandHandler $handler;

    public function __construct(
        ApiSerializer $serializer,
        GetCategoryGamesCommandHandler $handler
    ) {
        $this->serializer = $serializer;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, int $categoryId): Response
    {
        $command = new GetCategoryGamesCommand(
            $categoryId,
            (int) $request->get('offset', 0),
            (int) $request->get('limit', 50)
        );

        $gameListOutputDTO = $this->handler->handle($command);

        $data = [
            'games' => $this->serializer->normalize($gameListOutputDTO->getGames(), 'json'),
        ];
        $meta = [
            'total' => $gameListOutputDTO->getTotal(),
        ];

        return new SuccessJsonResponse(['data' => $data, 'meta' => $meta], Response::HTTP_OK);
    }
}

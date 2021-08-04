<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\GetGame\GetGameCommand;
use App\Application\Game\Command\GetGame\GetGameCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetGameAction
{
    private ApiSerializer $serializer;
    private GetGameCommandHandler $handler;

    public function __construct(
        ApiSerializer $serializer,
        GetGameCommandHandler $handler
    ) {
        $this->serializer = $serializer;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, string $gameId): Response
    {
        $command = new GetGameCommand($gameId);
        $gameOutputDTO = $this->handler->handle($command);

        $data = $this->serializer->normalize($gameOutputDTO);

        return new SuccessJsonResponse(['data' => $data], Response::HTTP_OK);
    }
}

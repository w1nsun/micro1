<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\CreateOrUpdateGame\CreateOrUpdateGamesCommand;
use App\Application\Game\Command\CreateOrUpdateGame\CreateOrUpdateGamesCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Exception\ValidationHttpException;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateOrUpdateCategoryGamesAction
{
    private ValidatorInterface $validator;
    private CreateOrUpdateGamesCommandHandler $handler;
    private ApiSerializer $serializer;

    public function __construct(
        ValidatorInterface                $validator,
        CreateOrUpdateGamesCommandHandler $handler,
        ApiSerializer                     $normalizer
    ) {
        $this->validator = $validator;
        $this->handler = $handler;
        $this->serializer = $normalizer;
    }

    public function __invoke(Request $request, int $categoryId): Response
    {
        $data = array_merge($request->toArray(), ['category_id' => $categoryId]);

        /** @var CreateOrUpdateGamesCommand $command */
        $command = $this->serializer->denormalize($data, CreateOrUpdateGamesCommand::class);

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($command);
        if (\count($errors) > 0) {
            throw new ValidationHttpException($errors);
        }

        $gameListOutputDTO = $this->handler->handle($command);

        $data = [
            'games' => $this->serializer->normalize($gameListOutputDTO->getGames(), 'json'),
        ];
        $meta = [
            'total' => $this->serializer->normalize($gameListOutputDTO->getTotal(), 'json'),
        ];

        return new SuccessJsonResponse(['data' => $data, 'meta' => $meta], Response::HTTP_OK);
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\DeleteGame\DeleteGamesCommand;
use App\Application\Game\Command\DeleteGame\DeleteGamesCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Exception\ValidationHttpException;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeleteCategoryGamesAction
{
    private ValidatorInterface $validator;
    private ApiSerializer $serializer;
    private DeleteGamesCommandHandler $handler;

    public function __construct(
        ValidatorInterface $validator,
        ApiSerializer $serializer,
        DeleteGamesCommandHandler $handler
    ) {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, int $categoryId): Response
    {
        $data = array_merge($request->toArray(), ['category_id' => $categoryId]);
        /** @var DeleteGamesCommand $command */
        $command = $this->serializer->denormalize($data, DeleteGamesCommand::class);

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($command);
        if (\count($errors) > 0) {
            throw new ValidationHttpException($errors);
        }

        $this->handler->handle($command);

        return new SuccessJsonResponse();
    }
}

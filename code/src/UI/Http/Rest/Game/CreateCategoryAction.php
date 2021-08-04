<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\CreateOrUpdateCategoryCommand;
use App\Application\Game\Command\CreateCategory\CreateCategoryCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Exception\ValidationHttpException;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateCategoryAction
{
    private ValidatorInterface $validator;
    private CreateCategoryCommandHandler $handler;
    private ApiSerializer $serializer;

    public function __construct(
        ValidatorInterface           $validator,
        CreateCategoryCommandHandler $handler,
        ApiSerializer                $serializer
    ) {
        $this->validator = $validator;
        $this->handler = $handler;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CreateOrUpdateCategoryCommand $command */
        $command = $this->serializer->denormalize($request->toArray(), CreateOrUpdateCategoryCommand::class);

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($command, null, ['create']);
        if (\count($errors) > 0) {
            throw new ValidationHttpException($errors);
        }

        $categoryOutputDTO = $this->handler->handle($command);
        $data = $this->serializer->normalize($categoryOutputDTO, 'json');

        return new SuccessJsonResponse(['data' => $data], Response::HTTP_CREATED);
    }
}

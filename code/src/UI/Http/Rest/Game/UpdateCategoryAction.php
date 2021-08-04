<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Game;

use App\Application\Game\Command\CreateOrUpdateCategoryCommand;
use App\Application\Game\Command\UpdateCategory\UpdateCategoryCommandHandler;
use App\Application\Shared\Serializer\ApiSerializer;
use App\UI\Exception\ValidationHttpException;
use App\UI\Http\Response\SuccessJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateCategoryAction
{
    private ValidatorInterface $validator;
    private UpdateCategoryCommandHandler $handler;
    private ApiSerializer $serializer;

    public function __construct(
        ValidatorInterface           $validator,
        UpdateCategoryCommandHandler $handler,
        ApiSerializer                $serializer
    ) {
        $this->validator = $validator;
        $this->handler = $handler;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, int $categoryId): Response
    {
        $data = array_merge($request->toArray(), ['category_id' => $categoryId]);

        /** @var CreateOrUpdateCategoryCommand $command */
        $command = $this->serializer->denormalize($data, CreateOrUpdateCategoryCommand::class);

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($command, null, ['update']);
        if (\count($errors) > 0) {
            throw new ValidationHttpException($errors);
        }

        $categoryOutputDTO = $this->handler->handle($command);
        $data = $this->serializer->normalize($categoryOutputDTO, 'json');

        return new SuccessJsonResponse(['data' => $data], Response::HTTP_OK);
    }
}

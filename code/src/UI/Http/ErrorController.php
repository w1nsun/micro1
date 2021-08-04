<?php

declare(strict_types=1);

namespace App\UI\Http;

use App\Application\Game\Exception\NotFoundExceptionInterface;
use App\UI\Constraint\ConstraintViolationConverter;
use App\UI\Exception\ValidationHttpException;
use App\UI\Http\Enum\ResponseStatusEnum;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ConstraintViolationConverter $constraintViolationConverter;

    public function __construct(ConstraintViolationConverter $constraintViolationConverter)
    {
        $this->constraintViolationConverter = $constraintViolationConverter;
    }

    public function error(\Throwable $exception, ?DebugLoggerInterface $logger): JsonResponse
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof NotFoundExceptionInterface) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        $this->log($exception, $statusCode);

        if ($exception instanceof ValidationHttpException) {
            return new JsonResponse(
                [
                    'status' => ResponseStatusEnum::ERROR,
                    'errors' => $this->constraintViolationConverter->toArray($exception->getViolationList()),
                ],
                $statusCode
            );
        }

        return new JsonResponse(
            [
                'status' => ResponseStatusEnum::ERROR,
                'message' => $exception->getMessage(),
            ],
            $statusCode
        );
    }

    private function log(\Throwable $exception, int $statusCode): void
    {
        $context = [
            'statusCode' => $statusCode,
            'message' => $exception->getMessage(),
            'trace' => $exception->getTrace(),
        ];

        if ($statusCode >= 500) {
            $this->logger->error('HTTP_RESPONSE_ERROR', $context);

            return;
        }

        $this->logger->warning('HTTP_RESPONSE_ERROR', $context);
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationHttpException extends HttpException
{
    private ConstraintViolationList $violationList;

    public function __construct(
        ConstraintViolationList $violationList,
        int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
        \Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        $this->violationList = $violationList;
        $message = 'Validation errors';

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getViolationList(): ConstraintViolationList
    {
        return $this->violationList;
    }
}

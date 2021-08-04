<?php

declare(strict_types=1);

namespace App\UI\Http\Response;

use App\UI\Http\Enum\ResponseStatusEnum;
use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessJsonResponse extends JsonResponse
{
    public function __construct(array $data = [], int $status = 200, array $headers = [], bool $json = false)
    {
        if (false === $json) {
            $data = array_merge(['status' => ResponseStatusEnum::SUCCESS], $data);
        }

        parent::__construct($data, $status, $headers, $json);
    }
}

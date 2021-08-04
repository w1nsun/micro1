<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Logger;

class CommonProcessor
{
    private static string $requestId;

    public function __construct()
    {
        self::$requestId = md5(uniqid('', true));
    }

    public function __invoke(array $record): array
    {
        $record['extra']['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $record['extra']['requestId'] = self::getRequestId();

        return $record;
    }

    private static function getRequestId()
    {
        return $_SERVER['X_REQUEST_ID'] ?? self::$requestId;
    }
}

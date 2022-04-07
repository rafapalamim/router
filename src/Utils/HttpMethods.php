<?php

declare(strict_types=1);

namespace Router\Utils;

class HttpMethods
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';

    const ALL = [self::GET, self::POST, self::PUT, self::PATCH, self::DELETE];

    public static function isValid(string $method): bool
    {
        return in_array($method, self::ALL);
    }
}

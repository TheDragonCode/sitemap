<?php

namespace Helldar\Sitemap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidatorException extends HttpException
{
    public function __construct(string $message = null, ?int $code = 0)
    {
        $message = $message ?: 'Validation error of data sent manually';
        $code    = $code ?: 400;

        parent::__construct($code, $message, null, [], $code);
    }
}

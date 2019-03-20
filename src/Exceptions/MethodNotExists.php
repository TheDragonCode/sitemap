<?php

namespace Helldar\Sitemap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class MethodNotExists extends HttpException
{
    public function __construct(string $message = null, ?int $code = 0)
    {
        $code = $code ?: 405;

        parent::__construct($code, $message, null, [], $code);
    }
}

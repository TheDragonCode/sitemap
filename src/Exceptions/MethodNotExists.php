<?php

namespace Helldar\Sitemap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class MethodNotExists extends HttpException
{
    /**
     * MethodNotExists constructor.
     *
     * @param null $message
     * @param \Exception|null $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct($message = null, \Exception $previous = null, $headers = [], $code = 0)
    {
        $code = $code ?: 405;

        parent::__construct($code, $message, $previous, $headers, $code);
    }
}

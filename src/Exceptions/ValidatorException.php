<?php

namespace Helldar\Sitemap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidatorException extends HttpException
{
    /**
     * ValidatorErrorException constructor.
     *
     * @param null            $message
     * @param \Exception|null $previous
     * @param int             $code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        $message = $message ?: 'Validation error of data sent manually';
        $code    = $code ?: 400;

        parent::__construct($code, $message, $previous, [], $code);
    }
}

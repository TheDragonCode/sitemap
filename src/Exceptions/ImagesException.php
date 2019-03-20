<?php

namespace Helldar\Sitemap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ImagesException extends HttpException
{
    public function __construct(string $message = null, ?int $code = 0)
    {
        $message = $message ?: 'Error handling the list of images to generate a sitemap.';
        $code    = $code ?: 400;

        parent::__construct($code, $message, null, [], $code);
    }
}

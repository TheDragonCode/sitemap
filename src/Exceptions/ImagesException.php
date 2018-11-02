<?php

namespace Helldar\Sitemap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ImagesException extends HttpException
{
    /**
     * SitemapManualUrlException constructor.
     *
     * @param null $message
     * @param \Exception|null $previous
     * @param int $code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        $message = $message ?: 'Error handling the list of images to generate a sitemap.';
        $code    = $code ?: 400;

        parent::__construct($code, $message, $previous, [], $code);
    }
}

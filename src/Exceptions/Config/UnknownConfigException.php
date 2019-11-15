<?php

namespace Helldar\Sitemap\Exceptions\Config;

use Exception;

class UnknownConfigException extends Exception
{
    public function __construct()
    {
        parent::__construct('Unknown configuration store type', 500);
    }
}

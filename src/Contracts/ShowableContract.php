<?php

namespace Helldar\Sitemap\Contracts;

use Symfony\Component\HttpFoundation\Response;

interface ShowableContract
{
    public function show(): Response;
}

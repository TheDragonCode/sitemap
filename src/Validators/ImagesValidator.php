<?php

namespace Helldar\Sitemap\Validators;

use Helldar\Sitemap\Exceptions\ImagesException;
use Helldar\Sitemap\Facades\ValidationFacade;

class ImagesValidator extends ValidationFacade
{
    protected $exception = ImagesException::class;

    protected function rules(): array
    {
        return [
            'items'                       => ['array', 'min:0', 'max:500'],
            'items.images'                => ['required_with:items', 'array', 'min:1', 'max:1000'],
            'items.images.*.loc'          => ['required_with:items.images', 'url', 'max:255'],
            'items.images.*.caption'      => ['string', 'max:255'],
            'items.images.*.geo_location' => ['string', 'max:255'],
            'items.images.*.title'        => ['string', 'max:255'],
            'items.images.*.license'      => ['string', 'max:255'],
        ];
    }
}

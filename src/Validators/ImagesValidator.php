<?php

namespace Helldar\Sitemap\Validators;

use DragonCode\Core\Xml\Abstracts\Validation;

class ImagesValidator extends Validation
{
    protected function rules(): array
    {
        return [
            '*.loc'                     => ['url', 'max:255'],
            '*.*.images'                => ['required_with:*', 'array', 'min:1', 'max:1000'],
            '*.*.images.*.loc'          => ['required_with:*.*.images', 'url', 'max:255'],
            '*.*.images.*.caption'      => ['string', 'max:255'],
            '*.*.images.*.geo_location' => ['string', 'max:255'],
            '*.*.images.*.title'        => ['string', 'max:255'],
            '*.*.images.*.license'      => ['string', 'max:255'],
        ];
    }
}

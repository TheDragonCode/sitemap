<?php

namespace Helldar\Sitemap\Validators;

use Helldar\Sitemap\Exceptions\ManualUrlException;
use Helldar\Sitemap\Facades\ValidationFacade;
use Helldar\Sitemap\Helpers\Variables;
use Illuminate\Validation\Rule;

class Manual extends ValidationFacade
{
    protected $exception = ManualUrlException::class;

    protected function rules(): array
    {
        $frequencies = Variables::getFrequencies();

        return [
            'items'              => ['required', 'array', 'min:1', 'max:50000'],
            'items.*.loc'        => ['required', 'url', 'max:255'],
            'items.*.changefreq' => ['string', 'max:20', Rule::in($frequencies)],
            'items.*.lastmod'    => ['date'],
            'items.*.priority'   => ['numeric'],
        ];
    }
}

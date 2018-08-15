<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Exceptions\SitemapManualUrlException;
use Helldar\Sitemap\Exceptions\ValidatorException;
use Illuminate\Validation\Rule;

class Manual
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * Manual constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        if (!$this->validate(compact('items'))) {
            throw new SitemapManualUrlException();
        }

        $this->items = $items;
    }

    /**
     * Get all items.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->items;
    }

    /**
     * Validate that all elements of the array are correct.
     *
     * @param $data
     *
     * @return bool
     */
    private function validate($data): bool
    {
        $validator = \Validator::make($data, $this->rules());

        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->first());
        }

        return true;
    }

    /**
     * List of rules for checking the correctness of the elements.
     *
     * @return array
     */
    private function rules(): array
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

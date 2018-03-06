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
    public function __construct($items = [])
    {
        if (!$this->validate(compact('items'))) {
            throw new SitemapManualUrlException();
        }

        $this->items = $items;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->items;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    private function validate($data)
    {
        $validator = \Validator::make($data, $this->rules());

        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->first());
        }

        return true;
    }

    /**
     * @return array
     */
    private function rules()
    {
        return [
            'items' => 'required|array|min:1|max:50000',
            'items.*.loc' => 'required|url|max:255',
            'items.*.changefreq' => ['string', 'max:255', Rule::in('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never')],
            'items.*.lastmod' => 'string',
            'items.*.priority' => 'numeric',
        ];
    }
}

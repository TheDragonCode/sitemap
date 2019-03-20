<?php

namespace Helldar\Sitemap\Facades;

use Helldar\Sitemap\Exceptions\ValidatorException;
use Helldar\Sitemap\Interfaces\ValidationInterface;
use Illuminate\Support\Facades\Validator;

abstract class ValidationFacade implements ValidationInterface
{
    protected $exception = ValidatorException::class;

    private $items = [];

    public function __construct($items)
    {
        $items = (array) $items;

        $this->validate(\compact('items'));

        $this->items = $items;
    }

    public function get(): array
    {
        return $this->items;
    }

    protected function rules(): array
    {
        return [];
    }

    private function validate(array $data = [])
    {
        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            throw new ValidatorException(\implode(PHP_EOL, $errors));
        }
    }
}

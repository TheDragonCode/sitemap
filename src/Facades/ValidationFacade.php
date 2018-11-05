<?php

namespace Helldar\Sitemap\Facades;

use Helldar\Sitemap\Exceptions\ImagesException;
use Helldar\Sitemap\Exceptions\ValidatorException;
use Helldar\Sitemap\Interfaces\ValidationInterface;
use Illuminate\Support\Facades\Validator;

class ValidationFacade implements ValidationInterface
{
    protected $exception = ValidatorException::class;

    private $items = [];

    public function __construct($items)
    {
        $items = (array) $items;

        if (!$this->validate(compact('items'))) {
            throw new ImagesException;
        }

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

    private function validate(array $data = []): bool
    {
        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            throw new ValidatorException(implode(PHP_EOL, $errors));
        }

        return true;
    }
}

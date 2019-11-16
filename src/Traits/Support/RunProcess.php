<?php

namespace Helldar\Sitemap\Traits\Support;

use Illuminate\Support\Str;

trait RunProcess
{
    protected function run(): void
    {
        array_map(function ($method) {
            $method = $this->methodName($method);

            if (method_exists($this, $method)) {
                call_user_func([$this, $method]);
            }
        }, get_class_methods($this));
    }

    private function methodName(string $method): string
    {
        $name = "run_{$method}_process";

        return Str::studly($name);
    }
}

# Sitemap

A simple sitemap generator for PHP Framework.

## Using


## Installation

<a name="install-with-composer"/>
### With Composer

```
$ composer require andrey-helldar/sitemap
```

```json
{
    "require": {
        "andrey-helldar/sitemap": "~1.0"
    }
}
```

```php
<?php
require 'vendor/autoload.php';

use Helldar\Sitemap;

printf("Now: %s", Sitemap::generate());
```


<a name="install-nocomposer"/>
### Without Composer

Why are you not using [composer](http://getcomposer.org/)? Download [Sitemap.php](https://github.com/andrey-helldar/Sitemap/blob/master/src/Sitemap/Sitemap.php) from the repo and save the file into your project path somewhere.

```php
<?php
require 'path/to/Sitemap.php';

use Helldar\Sitemap;

printf("Now: %s", Sitemap::generate());
```

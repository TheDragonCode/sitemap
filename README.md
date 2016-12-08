# Sitemap for Laravel 5.3+

<p align="center">
A simple sitemap generator for PHP Framework.
</p>


<p align="center">
<a href="https://travis-ci.org/andrey-helldar/sitemap"><img src="https://travis-ci.org/andrey-helldar/sitemap.svg?branch=master&style=flat-square" alt="Build Status" /></a>
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://img.shields.io/packagist/dt/andrey-helldar/sitemap.svg?style=flat-square" alt="Total Downloads" /></a>
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/stable" alt="Latest Stable Version" /></a>
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/unstable" alt="Latest Unstable Version" /></a>
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/license" alt="License" /></a>
</p>


<p align="center">
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://img.shields.io/scrutinizer/g/andrey-helldar/sitemap.svg?style=flat-square" alt="Quality Score" /></a>
<a href="https://www.versioneye.com/php/andrey-helldar:sitemap/dev-master"><img src="https://www.versioneye.com/php/andrey-helldar:sitemap/dev-master/badge?style=flat-square" alt="Dependency Status" /></a>
<a href="https://styleci.io/repos/45746985"><img src="https://styleci.io/repos/75637284/shield" alt="StyleCI" /></a>
<a href="https://php-eye.com/package/andrey-helldar/sitemap"><img src="https://php-eye.com/badge/andrey-helldar/sitemap/tested.svg?style=flat" alt="PHP-Eye" /></a>
</p>


## Installation

Require this package with composer using the following command:

```
composer require andrey-helldar/sitemap
```

or

```json
{
    "require": {
        "andrey-helldar/sitemap": "~1.0"
    }
}
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Helldar\Sitemap\SitemapServiceProvider::class,
```


You can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\Sitemap\SitemapServiceProvider"
```


You can now getting sitemap.xml:

```
http://mysite.dev/sitemap.xml
```


## Configuration

See at `config/sitemap.php`:

    `filename`          - The file name to save. Default, `sitemap.xml`.
    `cache`             - Caching time in minutes. Set `0` to disable cache. Default: 0.
    `age`               - Age data in minutes, over which references will not be included in the sitemap. Default: 180 days.
    `age_column`        - For some column search. Default: updated_at.
    `frequency`         - This value indicates how frequently the content at a particular URL is likely to change.
    `last_modification` - The time the URL was last modified. This information allows crawlers to avoid redrawing documents that haven't changed.

    `items` [
        [
            'model' => User::class,  // Eloquent Model.
            'route' => 'user::show', // Route for generate URL.
            'keys'  => [             // Keys for route.
                'category' => 'category_id', // `category` - key, `category_id` - field from table.
                'id'       => 'user_id',     // `id` - key, `user_id` - field from table.
            ],
            'priority' => 0.8,       // Sets the priority links
        ],
    ]


If you have any questions in the installation and use of the package, you can always [watch the video guide](https://youtu.be/1WaBqg7sW-s).


## Using

There are two options for using the package:

### Option 1

Site maps generated on the basis of these "simple" models in the configuration file:

```php
echo \Helldar\Sitemap\Factory::generate();
```

or

### Option 2

Transfer the finished array package to generate a site map with more complex URL:

```php
$sitemap = new \Helldar\Sitemap\Factory('myfile.xml');
$sitemap->set($loc, $lastmod, $priority);
echo $sitemap->get();
```

If you create a class as a parameter to pass the file name, the file will be saved with the specified name at the end of all the compiled files (`Factory('myfile.xml')`).

Example:
```php
function index()
{
    $faker   = Factory::create();
    $sitemap = new \Helldar\Sitemap\Factory('myfile.xml');

    for ($i = 0; $i < 10; $i++) {
        $loc      = url($faker->unique()->slug);
        $lastmod  = Carbon::now()->timestamp;
        $priority = 0.9;

        $sitemap->set($loc, $lastmod, $priority);
    }

    return $sitemap->get();
}
// Return all users from database and save to file `/public/myfile.xml`.
```


## Support Library

You can donate via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=94B8LCPAPJ5VG), Yandex Money (410012608840929), WebMoney (Z124862854284)

## Copyright and License

Sitemap was written by Andrey Helldar for the Laravel Framework 5.3 or later, and is released under the MIT License. See the LICENSE file for details.

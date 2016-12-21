# Sitemap for Laravel 5.3+

A simple sitemap generator for PHP Framework.

![Sitemap for Laravel 5.3+](https://cloud.githubusercontent.com/assets/10347617/21395348/7f91418c-c7df-11e6-8c1d-0f91fbe77ddc.jpg)

<p align="center">
<a href="https://travis-ci.org/andrey-helldar/sitemap"><img src="https://travis-ci.org/andrey-helldar/sitemap.svg?branch=master&style=flat-square" alt="Build Status" /></a>
<a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://img.shields.io/packagist/dt/andrey-helldar/sitemap.svg?style=flat-square" alt="Total Downloads" /></a>
<a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
<a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/license?format=flat-square" alt="License" /></a>
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
    `clear_old`         - Clear old links in database table. Default: false. 
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


If you have any questions in the installation and use of the package, you can always [watch the video guide for version 1.0.2](https://youtu.be/1WaBqg7sW-s).


## Using

There are two options for using the package:

### 1. Simple

Site maps generated on the basis of these "simple" models in the configuration file:

```php
echo \Helldar\Sitemap\Factory::generate('myfile.xml');
```

or

### 2. Manual

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

#### Console command

To delete old records database, use the following command:
```bash
php artisan sitemap:clear
```

or configure the scheduler:

Add command to `schedule` method in `app/Console/Kernel.php`:
```php
$schedule->command('sitemap:clear')->daily();
```

Of course, there are a variety of schedules you may assign to your task:
[Schedule Frequency Options in Laravel](https://laravel.com/docs/5.3/scheduling#schedule-frequency-options)


## Support Library

You can donate via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=94B8LCPAPJ5VG), [Yandex Money](https://money.yandex.ru/quickpay/shop-widget?account=410012608840929&quickpay=shop&payment-type-choice=on&mobile-payment-type-choice=on&writer=seller&targets=Andrey+Helldar%3A+Open+Source+Projects&targets-hint=&default-sum=&button-text=04&mail=on&successURL=), WebMoney (Z124862854284, R343524258966)

## Copyright and License

Sitemap was written by Andrey Helldar for the Laravel Framework 5.3 or later, and is released under the MIT License. See the LICENSE file for details.

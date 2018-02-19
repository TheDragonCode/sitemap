# Sitemap for Laravel 5.4+

A simple sitemap generator for PHP Framework.

![Sitemap for Laravel 5.4+](https://user-images.githubusercontent.com/10347617/36281384-866335d6-12ae-11e8-92ec-c29bc879c213.png)

<p align="center">
<a href="https://travis-ci.org/andrey-helldar/sitemap"><img src="https://travis-ci.org/andrey-helldar/sitemap.svg?branch=master&style=flat-square" alt="Build Status" /></a>
<a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://img.shields.io/packagist/dt/andrey-helldar/sitemap.svg?style=flat-square" alt="Total Downloads" /></a>
<a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
<a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
<a href="https://github.com/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/license?format=flat-square" alt="License" /></a>
</p>


<p align="center">
<a href="https://www.versioneye.com/php/andrey-helldar:sitemap/dev-master"><img src="https://www.versioneye.com/php/andrey-helldar:sitemap/dev-master/badge?style=flat-square" alt="Dependency Status" /></a>
<a href="https://styleci.io/repos/45746985"><img src="https://styleci.io/repos/75637284/shield" alt="StyleCI" /></a>
<a href="https://php-eye.com/package/andrey-helldar/sitemap"><img src="https://php-eye.com/badge/andrey-helldar/sitemap/tested.svg?style=flat" alt="PHP-Eye" /></a>
</p>


## Installation

To get the latest version of Laravel Sitemap, simply require the project using [Composer](https://getcomposer.org):

```
composer require andrey-helldar/sitemap
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/sitemap": "~3.0"
    }
}
```

If you don't use auto-discovery, add the `ServiceProvider` to the providers array in `config/app.php`:

```php
Helldar\Sitemap\ServiceProvider::class,
```

You can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\Sitemap\ServiceProvider"
```

Now you can use a sitemap() helper.


## Configuration

To configure the generation, you need to fill the `models` array in the `config/sitemap.php` file:

```php
'models' => [
    \App\User::class => [
        'route' => 'route.name',
        'route_parameters' => [
            'slug' => 'table_field_for_slug',
            'foo'  => 'table_field_for_foo',
            'bar'  => 'table_field_for_bar',
            'baz',
        ],
        'lastmod' => 'updated_at',
        'frequency' => 'daily',
        'priority'  => 0.8,
    ],
]
```

As the key of the array, you must use the model name for which the rules will be defined.

 * **route** - the name of the route to generate the URL.
 * **route_parameters** - the parameters to be passed to the URL generation method, where:
    * the key is the parameter name for the routing. If the name of the routing parameter matches the name of the column in the database, you can specify only the value.
    * the value is the name of the column in the database to substitute the value.
 * **lastmod** - is the name of the column containing the record date. In case of absence, the current date is used. If the model does not need to take into account the time field, set the parameter `lastmod` to `false`.
 * **frequency** - is the value of the refresh rate of the content. This is necessary for some search robots.
 * **priority** - is the priority of the reference for model records.

If any of the model values are undefined, a global value will be used.


## Using

### Show

To display the content on the screen, use the `show()` method:

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

return sitemap()
         ->models($query1, $query2, $query3)
         ->show();
```

To return the content of the page, add any route:
```php
app('route')->get('sitemap', function() {
    $query1 = \App\Catalog::query()->where('id', '>', '1000');
    $query2 = \App\News::query()->where('category_id', 10);
    $query3 = \App\Pages::query();

    return sitemap()
             ->models($query1, $query2, $query3)
             ->show();
});
```

And go to your URL. Example: `http://mysite.dev/sitemap`.

### Save

To save the contents to a file, use the `save()` method:

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

return sitemap()
         ->models($query1, $query2, $query3)
         ->save();
```


## Support Package

You can donate via [Yandex Money](https://money.yandex.ru/quickpay/shop-widget?account=410012608840929&quickpay=shop&payment-type-choice=on&mobile-payment-type-choice=on&writer=seller&targets=Andrey+Helldar%3A+Open+Source+Projects&targets-hint=&default-sum=&button-text=04&mail=on&successURL=).


## Copyright and License

`Laravel Sitemap` was written by Andrey Helldar for the Laravel framework 5.4 or later, and is released under the MIT License. See the [LICENSE](LICENSE) file for details.

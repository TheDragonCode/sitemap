# Sitemap for Laravel 5.6+

<img src="https://preview.dragon-code.pro/TheDragonCode/sitemap.svg?brand=laravel" alt="Laravel Sitemap"/>

A simple sitemap generator for PHP Framework.

<p align="center">
    <a href="https://styleci.io/repos/75637284"><img src="https://styleci.io/repos/75637284/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://img.shields.io/packagist/dt/andrey-helldar/sitemap.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/sitemap"><img src="https://poser.pugx.org/andrey-helldar/sitemap/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/sitemap/license?format=flat-square" alt="License" /></a>
</p>


## Installation

For the 5.4 and 5.5 version of Laravel Framework, use [version 3](https://github.com/andrey-helldar/sitemap/tree/3.x).

To get the latest version of Laravel Sitemap, simply require the project using [Composer](https://getcomposer.org):

```
composer require andrey-helldar/sitemap
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/sitemap": "^5.0"
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

Now you can use a `app('sitemap')` method.


## Upgrade Guides

* [Upgrading from 4.x to 5.x](guides/UPGRADE_4X_TO_5X.md)


## Configuration

To configure the generation, you need to fill the `models` array in the `config/sitemap.php` file:

```php
'models' => [
    \App\User::class => [
        'route' => 'route.name',
        'route_parameters' => [
            'slug' => 'table_field_for_slug',
            'foo'  => 'table_field_for_foo',
            bar'  => 'my_relation.slug',
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
    * the value is the name of the column in the database to substitute the value. If the value needs to be taken from the link, then you should specify the appeal through a point. For example: `'bar' => 'my_relation.slug'`.
 * **lastmod** - is the name of the column containing the record date. In case of absence, the current date is used. If the model does not need to take into account the time field, set the parameter `lastmod` to `false`.
 * **frequency** - is the value of the refresh rate of the content. This is necessary for some search robots. You can also use constants from `Helldar\Sitemap\Services\Sitemap` class.
 * **priority** - is the priority of the reference for model records.

If any of the model values are undefined, a global value will be used.


## Using

### Manual

You can also transfer an array of items created manually:
```php
use Carbon\Carbon;
use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Services\Sitemap;

$items_a = [];
$items_b = [];

for ($i = 0; $i < 3; $i++) {
    $item = app('sitemap')->makeItem()
        ->changefreq('weekly')
        ->lastmod(Carbon::now())
        ->loc("http://mysite.local/page/" . $i);

    array_push($items_a, $item);
}


for ($i = 0; $i < 3; $i++) {
    $item = app('sitemap')->makeItem()
        ->changefreq(Variables::FREQUENCY_WEEKLY)
        ->lastmod(Carbon::now())
        ->loc("http://mysite.local/offers/" . $i);

    array_push($items_b, $item);
}

return app('sitemap')
         ->manual($items_a, $items_b)
         ->show();
```

Returned:
```xml
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <changefreq>weekly</changefreq>
        <lastmod>2018-03-06T12:30:17+03:00</lastmod>
        <loc>http://mysite.local/page/0</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <changefreq>weekly</changefreq>
        <lastmod>2018-03-06T12:38:24+03:00</lastmod>
        <loc>http://mysite.local/page/1</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <changefreq>weekly</changefreq>
        <lastmod>2018-03-06T12:30:17+03:00</lastmod>
        <loc>http://mysite.local/page/2</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <changefreq>weekly</changefreq>
        <lastmod>2018-03-06T12:30:17+03:00</lastmod>
        <loc>http://mysite.local/offers/0</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <changefreq>weekly</changefreq>
        <lastmod>2018-03-06T12:38:24+03:00</lastmod>
        <loc>http://mysite.local/offers/1</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <changefreq>weekly</changefreq>
        <lastmod>2018-03-06T12:30:17+03:00</lastmod>
        <loc>http://mysite.local/offers/2</loc>
        <priority>0.5</priority>
    </url>
</urlset>
```

Also you can combine the data from the models builders with the transferred manually:
```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

return app('sitemap')
         ->builders($query1, $query2, $query3)
         ->manual($items1, $items2, $items3)
         ->show();
```

### Images

Your can also transfer an array of image items created manually:
```php
use Helldar\Sitemap\Services\Sitemap;

$items = [];

for ($i = 0; $i < 2; $i++) {
    $item = app('sitemap')->makeImages()
        ->loc("http://mysite.local/page/" . $i)
        ->image("http://mysite.local/images/1.jpg", "My Title 1-".$i, "Caption for image", "Limerick, Ireland", "Royalty free")
        ->image("http://mysite.local/images/2.jpg", "My Title 2-".$i)
        ->image("http://mysite.local/images/3.jpg");
    
    array_push($items, $item);
}

return app('sitemap')
         ->images($items)
         ->show();
```

Returned:
```xml
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc>http://mysite.local/page/0</loc>
        <image:image>
            <image:loc>http://mysite.local/images/1.jpg</image:loc>
            <image:title>My Title 1-0</image:title>
            <image:caption>Caption for image</image:caption>
            <image:geo_location>Limerick, Ireland</image:geo_location>
            <image:license>Royalty free</image:license>
        </image:image>
        <image:image>
            <image:loc>http://mysite.local/images/2.jpg</image:loc>
            <image:title>My Title 2-0</image:title>
        </image:image>
        <image:image>
            <image:loc>http://mysite.local/images/3.jpg</image:loc>
        </image:image>
    </url>
    <url>
        <loc>http://mysite.local/page/1</loc>
        <image:image>
            <image:loc>http://mysite.local/images/1.jpg</image:loc>
            <image:title>My Title 1-1</image:title>
            <image:caption>Caption for image</image:caption>
            <image:geo_location>Limerick, Ireland</image:geo_location>
            <image:license>Royalty free</image:license>
        </image:image>
        <image:image>
            <image:loc>http://mysite.local/images/2.jpg</image:loc>
            <image:title>My Title 2-1</image:title>
        </image:image>
        <image:image>
            <image:loc>http://mysite.local/images/3.jpg</image:loc>
        </image:image>
    </url>
</urlset>
```

Attention! Due to the different structure of documents, when trying to call method `show()`, an image map will be shown only if there are no calls to other methods.

Example:
```php
// Will show the image map.
return app('sitemap')
         ->images($items)
         ->show();

// Shows the map for `builders`. The image map will be ignored.
return app('sitemap')
         ->builders($query1, $query2, $query3)
         ->images($items)
         ->show();
```

The same principle applies when saving to one file - images will be ignored. But when saving to several files, the map will be successfully created.


### Show

To display the content on the screen, use the `show()` method:

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

return app('sitemap')
         ->builders($query1, $query2, $query3)
         ->show();
```

To return the content of the page, add any route:
```php
app('route')->get('sitemap', function() {
    $query1 = \App\Catalog::query()->where('id', '>', '1000');
    $query2 = \App\News::query()->where('category_id', 10);
    $query3 = \App\Pages::query();

    return app('sitemap')
             ->builders($query1, $query2, $query3)
             ->show();
});
```

And go to your URL. Example: `http://mysite.dev/sitemap`.

### Save

#### If the option `separate_files` is DISABLED

To save the contents to the file, use the `save()` method:

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

app('sitemap')
     ->builders($query1, $query2, $query3)
     ->save();
```

If you want to save multiple files, pass the path to the file as a parameter to the `save()` method:

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

app('sitemap')
     ->builders($query1, $query2, $query3)
     ->save('sitemap-1.xml');

app('sitemap')
     ->builders($query1, $query2, $query3)
     ->save('foo/bar/sitemap-2.xml');
```

#### If the option `separate_files` is ENABLED

To save the contents to the separated files, use the `save()` method with `'separate_files' => true` parameter in [config/sitemap.php](src/config/sitemap.php) file.

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

app('sitemap')
     ->builders($query1, $query2, $query3)
     ->save();
```

In this case, the name of the file will be the default name from the settings: `'filename' => 'sitemap.xml'`. The files are saved through the `Storage` facade (see [config/sitemap.php](src/config/sitemap.php)).

Each model builder will be processed and saved in a separate file, and the shared file will contain references to it (with the selected a `public` storage name):

```
/storage/sitemap.xml   // general file
/storage/sitemap-1.xml // generated file for the $query1 collection
/storage/sitemap-2.xml // generated file for the $query2 collection
/storage/sitemap-3.xml // generated file for the $query3 collection
```

```xml
<?xml version="1.0" encoding="utf-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <lastmod>2018-07-05T13:51:40+00:00</lastmod>
    <loc>http://example.com/storage/sitemap-1.xml</loc>
  </sitemap>
  <sitemap>
    <lastmod>2018-07-05T13:51:41+00:00</lastmod>
    <loc>http://example.com/storage/sitemap-2.xml</loc>
  </sitemap>
  <sitemap>
    <lastmod>2018-07-05T13:51:41+00:00</lastmod>
    <loc>http://example.com/storage/sitemap-3.xml</loc>
  </sitemap>
</sitemapindex>
```

If you use a multi-domain application, you can specify the domain names of the generated links to other files, having pre-specified them in the settings:

```php
'domains' => [
    'foo' => env('APP_URL'), // http://example.com
    'bar' => 'http://foo.bar',
],
```

```php
app('sitemap')
     ->builders($query1, $query2, $query3)
     ->domain('foo')
     ->save();
     
app('sitemap')
     ->builders($query1, $query2, $query3)
     ->domain('bar')
     ->save();
```

This method will create files with the following links:

```xml
<?xml version="1.0" encoding="utf-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <lastmod>2018-07-05T13:51:40+00:00</lastmod>
    <loc>http://example.com/storage/sitemap-1.xml</loc>
  </sitemap>
  <sitemap>
    <lastmod>2018-07-05T13:51:41+00:00</lastmod>
    <loc>http://example.com/storage/sitemap-2.xml</loc>
  </sitemap>
  <sitemap>
    <lastmod>2018-07-05T13:51:41+00:00</lastmod>
    <loc>http://example.com/storage/sitemap-3.xml</loc>
  </sitemap>
</sitemapindex>
```

and

```xml
<?xml version="1.0" encoding="utf-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <lastmod>2018-07-05T13:51:40+00:00</lastmod>
    <loc>http://foo.bar/storage/sitemap-1.xml</loc>
  </sitemap>
  <sitemap>
    <lastmod>2018-07-05T13:51:41+00:00</lastmod>
    <loc>http://foo.bar/storage/sitemap-2.xml</loc>
  </sitemap>
  <sitemap>
    <lastmod>2018-07-05T13:51:41+00:00</lastmod>
    <loc>http://foo.bar/storage/sitemap-3.xml</loc>
  </sitemap>
</sitemapindex>
```

By default, the domain specified in `APP_URL` of `.env` file will be used.


If you want to save multiple files, pass the path to the file as a parameter to the `save($path)` method with `'separate_files' => true` parameter in [config/sitemap.php](src/config/sitemap.php) file:

```php
$query1 = \App\Catalog::query()->where('id', '>', '1000');
$query2 = \App\News::query()->where('category_id', 10);
$query3 = \App\Pages::query();

$manual_items = [];

for ($i = 0; $i < 3; $i++) {
    $item = app('sitemap')->makeItem()
        ->changefreq('weekly')
        ->lastmod(Carbon\Carbon::now())
        ->loc("http://mysite.local/page/" . $i);

    array_push($manual_items, $item);
}

app('sitemap')
     ->builders($query1, $query2, $query3)
     ->manual($manual_items)
     ->save(public_path('first.xml'));

app('sitemap')
     ->builders($query1, $query2, $query3)
     ->manual($manual_items)
     ->save(storage_path('foo/bar/second.xml'));
```

Files will be created:
```
/storage/first.xml   // general file
/storage/first-1.xml // generated file for the $query1 collection
/storage/first-2.xml // generated file for the $query2 collection
/storage/first-3.xml // generated file for the $query3 collection
/storage/first-4.xml // generated file for the $manual_items collection

/storage/foo/bar/second.xml   // general file
/storage/foo/bar/second-1.xml // generated file for the $query1 collection
/storage/foo/bar/second-2.xml // generated file for the $query2 collection
/storage/foo/bar/second-3.xml // generated file for the $query3 collection
/storage/foo/bar/second-4.xml // generated file for the $manual_items collection
```

## SEO

Sitemap is extremely useful when indexing a site by search bots. If you use a sitemap splitting into several files, you only need to add a link to the main file in the `robots.txt` file.

For example, you created several files:
```text
/storage/sitemaps/promo.xml
/storage/sitemaps/promo-1.xml
/storage/sitemaps/promo-2.xml
/storage/sitemaps/promo-3.xml
```

In the `robots.txt` file, you only need to specify a link to the main file:
```text
Sitemap: http://example.com/storage/sitemaps/promo.xml
```

All the rest of the search bots will do for you.

Enjoy!


## License

This package is licensed under the [MIT License](LICENSE).

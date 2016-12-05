# Sitemap

A simple sitemap generator for PHP Framework.


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


To install this package on only development systems, add the --dev flag to your composer command:
```
composer require --dev andrey-helldar/sitemap
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

    `cache`             - Caching time in minutes. Set `0` to disable cache. Default: 0.
    `age`               - Age data in days, over which references will not be included in the sitemap. Default: 180 days.
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

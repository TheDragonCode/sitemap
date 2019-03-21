# Upgrade from 4.x to 5.x

## Updating Dependencies

Update your `andrey-helldar/sitemap` dependency to `^5.0` in your `composer.json` file.

Update dependencies with the command:
```
composer update
```

## Methods

Function `sitemap()` is deprecated. Use `app('sitemap')` instead.


## Config

* Replace `formatOutput` option to `format_output` in [config/sitemap.php](../src/config/sitemap.php) file.


Yes, that's all 😊

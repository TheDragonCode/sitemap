# Upgrade guide

## 1.0.3 > 1.0.4

### Step 1
Run command in console:
```bash
php artisan vendor:publish --provider="Helldar\Sitemap\SitemapServiceProvider"
php artisan migrate
```

### Step 2
Add command to `schedule` method in `app/Console/Kernel.php`:
```php
$schedule->command('sitemap:clear')->daily();
```

Of course, there are a variety of schedules you may assign to your task:
[Schedule Frequency Options in Laravel](https://laravel.com/docs/5.3/scheduling#schedule-frequency-options)
# Laravel File Generator

This is package to integrate [Laravel File Generator](https://sirio.co.id) with [Laravel](https://laravel.com/).
It includes a ServiceProvider to register the generator.

## Installation
Require this package using composer.
```shell
composer require sirio/laravel-command
```

Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:
```php
'providers' => [
    // ...
    Sirio\LaravelCommand\App\Providers\SirioCommandServiceProvide::class,
];
```

# Laravel File Generator

This is package to integrate [Laravel File Generator](https://sirio.co.id) with [Laravel](https://laravel.com/).
It includes a ServiceProvider to register the generator.

## Installation
Require this package using composer.
```shell
composer require sirio/file_generator
```

Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:
```php
'providers' => [
    // ...
    Sirio\FileGenerator\FileGeneratorServiceProvider::class,
];
```

You should publish the `migration` and `config/filegenerator.php` file with:
```shell
php artisan vendor:publish --provider="Sirio\FileGenerator\FileGeneratorServiceProvider"
```

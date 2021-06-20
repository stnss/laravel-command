# Laravel Create Command
[![Packagist License](https://img.shields.io/github/license/stnss/laravel-command)](http://choosealicense.com/licenses/mit/)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sirio/laravel-command.svg)](https://packagist.org/packages/sirio/laravel-command)
[![Total Downloads](https://img.shields.io/packagist/dt/sirio/laravel-command.svg)](https://packagist.org/packages/sirio/laravel-command)

This is package to integrate [Laravel Create Command](https://github.com/stnss/laravel-command) with [Laravel](https://laravel.com/).
It includes a ServiceProvider to register the command.

## Installation
Require this package using composer.
```shell
composer require sirio/laravel-command
```

Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:
```php
'providers' => [
    // ...
    Sirio\LaravelCommand\SirioCommandServiceProvide::class,
];
```

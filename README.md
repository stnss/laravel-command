# Laravel Create Command
[![License](http://poser.pugx.org/sirio/laravel-command/license)](https://packagist.org/packages/sirio/laravel-command)
[![Version](http://poser.pugx.org/sirio/laravel-command/version)](https://packagist.org/packages/sirio/laravel-command)
[![Total Downloads](http://poser.pugx.org/sirio/laravel-command/downloads)](https://packagist.org/packages/sirio/laravel-command)

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

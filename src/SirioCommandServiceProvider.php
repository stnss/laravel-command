<?php

namespace Sirio\LaravelCommand;

use Illuminate\Support\ServiceProvider;
use Sirio\LaravelCommand\Console\Commands\ControllerCreateCommand;
use Sirio\LaravelCommand\Console\Commands\ModelCreateCommand;
use Sirio\LaravelCommand\Console\Commands\RequestCreateCommand;
use Sirio\LaravelCommand\Console\Commands\ViewCreateCommand;

class SirioCommandServiceProvider extends ServiceProvider {
    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerCreateCommand::class,
                ViewCreateCommand::class,
                RequestCreateCommand::class,
                ModelCreateCommand::class,
            ]);
        }
    }

    public function register() {
        
    }
}
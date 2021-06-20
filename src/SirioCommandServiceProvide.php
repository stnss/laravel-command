<?php

namespace Sirio\LaravelCommand;

use Illuminate\Support\ServiceProvider;
use Sirio\LaravelCommand\App\Console\Commands\ControllerCreateCommand;
use Sirio\LaravelCommand\App\Console\Commands\ViewCreateCommand;

class SirioCommandServiceProvide extends ServiceProvider {
    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerCreateCommand::class,
                ViewCreateCommand::class,
            ]);
        }
    }

    public function register() {
        
    }
}
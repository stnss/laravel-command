<?php

namespace Sirio\LaravelCommand\App\Providers;

use Illuminate\Support\ServiceProvider;
use Sirio\LaravelCommand\App\Console\Commands\ControllerSirioCommand;

class SirioCommandServiceProvide extends ServiceProvider {
    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerSirioCommand::class,
            ]);
        }
    }

    public function register() {
        
    }
}
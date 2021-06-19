<?php

namespace Sirio\LaravelCommand\App\Providers;

use Illuminate\Support\ServiceProvider;

class SirioCommandServiceProvide extends ServiceProvider {
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/console.php');
    }

    public function register() {
        
    }
}
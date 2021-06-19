<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspires', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
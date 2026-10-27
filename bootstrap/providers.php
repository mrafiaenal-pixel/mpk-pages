<?php

use App\Providers\AppServiceProvider;
use Barryvdh\LaravelDompdf\ServiceProvider as DompdfServiceProvider;

return [
    AppServiceProvider::class,
    DompdfServiceProvider::class,
];

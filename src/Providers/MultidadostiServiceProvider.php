<?php

namespace Agenciafmd\Multidadosti\Providers;

use Illuminate\Support\ServiceProvider;

class MultidadostiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 
    }

    public function register()
    {
        $this->loadConfigs();
    }

    protected function loadConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-multidadosti.php', 'laravel-multidadosti');
    }
}

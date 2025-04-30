<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pipeline\Pipeline;

class AutoDecorateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $bindings = config('decorators', []);

        foreach ($bindings as $interface => $setup) {
            $core = $setup['core'];
            $decorators = $setup['decorators'] ?? [];

            $this->app->singleton($interface, function ($app) use ($core, $decorators) {
                $base = $app->make($core);
            
                $decorated = $base;
            
                foreach ($decorators as $decoratorClass) {
                    $decorated = new $decoratorClass($decorated);
                }
            
                return $decorated;
            });
        }
    }
}


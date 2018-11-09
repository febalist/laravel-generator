<?php

namespace Febalist\Laravel\Generator;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class GeneratorServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\GenerateController::class,
                Commands\GenerateModel::class,
                Commands\GenerateResource::class,
                Commands\GenerateViews::class,
            ]);
        }
    }

    public function register()
    {
        $this->publishes([
            __DIR__.'/../stubs' => resource_path('stubs'),
        ]);
    }
}

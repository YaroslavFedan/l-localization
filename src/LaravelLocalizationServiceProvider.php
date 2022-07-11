<?php

namespace Dongrim\LaravelLocalization;

use Illuminate\Support\ServiceProvider;

class LaravelLocalizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResourses();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/localization.php', 'localization');
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . "/../config/localization.php" => config_path("localization.php")
        ], "config");
    }

    protected function registerResourses(): void
    {
        $this->registerFacades();
    }

    protected function registerFacades(): void
    {
        $this->app->singleton("Localization", function ($app) {
            return new LaravelLocalization();
        });
    }
}

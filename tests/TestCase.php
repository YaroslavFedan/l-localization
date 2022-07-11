<?php

namespace Dongrim\LaravelLocalization\Tests;

use Mockery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Dongrim\LaravelLocalization\Facades\Localization;
use Dongrim\LaravelLocalization\LaravelLocalizationServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected static $migration;

    protected $defaultLocale;

    protected $locales;

    public $middlewareClass;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelLocalizationServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        if (File::exists(__DIR__ . '/../config/localization.php')) {
            Config::set('localization', require(__DIR__ . '/../config/localization.php'));
        }
    }


    protected static function makeAnyRoute(
        string $locale = '',
        string $anotherPrefix = '',
        string $controllerAction = ''
    ): void {

        Route::group([
            'prefix' => self::prefix($locale, $anotherPrefix),
        ], function () use ($controllerAction) {
            Route::any($controllerAction, function () {
                return app()->getLocale();
            });
        });
    }

    protected function prefix($url, $anotherPrefix = '')
    {
        $request = Request::create($url);
        app()->instance('request', $request);

        return Localization::prefix($anotherPrefix);
    }

    protected function getFullUrl(string $locale = '', string $anotherPrefix = '', string $controllerAction = '')
    {
        $fullUrl = '';

        if ($this->hideDefaultLocaleInURL)
            $locale = ($locale !== $this->defaultLocale) ? $locale : "";

        if (!blank($locale))
            $fullUrl .= $locale;

        if (!blank($anotherPrefix)) {
            $fullUrl .= DIRECTORY_SEPARATOR . $anotherPrefix;
        }

        if (!blank($controllerAction)) {
            $fullUrl .= DIRECTORY_SEPARATOR . $controllerAction;
        }

        return ltrim(rtrim($fullUrl, '/'), '/');
    }


    protected function localizationMapping()
    {
        foreach ($this->exampleLocales as $key => $value) {
            $this->exampleLocales[$key] = Localization::localesMapping($value);
        }
    }
}

<?php

namespace Dongrim\LaravelLocalization\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Dongrim\LaravelLocalization\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dongrim\LaravelLocalization\Tests\Models\LocalizationExample;
use Dongrim\LaravelLocalization\Tests\database\seeds\LocalizationExampleSeeder;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected $exampleLocales;

    protected $defaultLocale;

    public function setUp(): void
    {
        parent::setUp();

        $this->defaultLocale = config('app.locale');
        $this->hideDefaultLocaleInURL = config('localization.hideDefaultLocaleInURL');

        $example = [
            'en' => ['some data'],
            'ru' => ['some data'],
            'uk' => ['some data']
        ];
        Config::set('localization.driver', 'default');
        Config::set('localization.default.supportedLocales', $example);

        $this->withoutExceptionHandling();
    }


    /** @test */
    public function aLocalesEqualsDefaultDriverLocales()
    {
        $locales = [
            'en' => ['some data'],
            'de' => ['some data'],
            'fr' => ['some data']
        ];
        Config::set('localization.driver', 'default');
        Config::set('localization.default.supportedLocales', $locales);
        $compare = collect(array_keys($locales));
        $this->assertEquals($compare, locales());
    }

    /** @test */
    public function aLocalesEqualsDefaultDriverLocalesWithLocaleMapping()
    {
        $example = [
            'en' => ['some data'],
            'de' => ['some data'],
            'uk' => ['some data']
        ];
        $compare = collect(['en', 'de', 'ua']);

        Config::set('localization.driver', 'default');
        Config::set('localization.default.supportedLocales', $example);
        Config::set('localization.localesMapping', ['uk' => 'ua']);

        $this->assertEquals($compare, locales());
    }

    /** @test */
    public function exampleLocalesEqualsDatabaseDriverLocales()
    {
        $example = ['en', 'ru', 'ua'];
        $compare = collect($example);
        Config::set('localization.driver', 'database');
        Config::set('localization.database.model_namespace', LocalizationExample::class);
        Config::set('localization.database.locale_key', 'locale');
        (new LocalizationExampleSeeder())->run($example);

        $this->assertEquals($compare, locales());
    }

    /** @test */
    public function exampleLocalesEqualsDatabaseDriverLocalesWithLocaleMapping()
    {
        $example = ['en', 'ru', 'uk'];
        $compare = collect(['en', 'ru', 'ua']);

        Config::set('localization.driver', 'database');
        Config::set('localization.database.model_namespace', LocalizationExample::class);
        Config::set('localization.database.locale_key', 'locale');
        Config::set('localization.localesMapping', ['uk' => 'ua']);

        (new LocalizationExampleSeeder())->run($example);

        $this->assertEquals($compare, locales());
    }

    /** @test */
    public function urlEqualsUrlWithPrefix()
    {
        foreach (locales() as $locale) {
            $fullUrl = $this->getFullUrl($locale);
            $prefix = $this->prefix($fullUrl);

            $this->assertEquals($fullUrl, $prefix);
        }
    }

    /** @test */
    public function routingWithoutAnyLocale()
    {
        $paths = ['/', '/someController', 'someController/someAction'];
        foreach ($paths  as $path) {
            self::makeAnyRoute('', '', $path);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method, $path);
                $this->assertSame(
                    200,
                    $response->getStatusCode(),
                    'No OK response for ' . $method . ' on route ' . $path . ' without locale.'
                );
            }
        }
    }


    /** @test */
    public function routingCanWorkWithPrefix()
    {
        $controllerAction = '/';

        foreach (locales()  as $locale) {
            self::makeAnyRoute($locale, '', $controllerAction);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method, $controllerAction);
                $this->assertSame(
                    200,
                    $response->getStatusCode(),
                    'No OK response for ' . $method . ' on locale ' . $locale . ' route.'
                );
            }
        }
    }


    /** @test */
    public function urlEqualsUrlWithPrefixWithAnotherPrefixAdded()
    {
        // another prefix
        $anotherPrefix = 'admin';

        foreach (locales() as $locale) {
            $fullUrl = $this->getFullUrl($locale, $anotherPrefix);
            $prefix = $this->prefix($fullUrl, $anotherPrefix);

            $this->assertEquals($fullUrl, $prefix);
        }
    }


    /** @test */
    public function routingCanWorkWithPrefixAndAnotherPrefix()
    {
        $controllerAction = '/';
        $anotherPrefix = 'admin';

        foreach (locales() as $locale) {
            self::makeAnyRoute($locale, $anotherPrefix, $controllerAction);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method, $this->getFullUrl($locale, $anotherPrefix, $controllerAction));
                $this->assertSame(
                    200,
                    $response->getStatusCode(),
                    'No OK response for ' . $method . ' on locale ' . $locale . ' route.'
                );
            }
        }
    }


    /** @test */
    public function routingCanWorkWithPrefixAndSomeControllerAction()
    {
        $anotherPrefix = '';
        $controllerAction = 'someController/someAction';

        foreach (locales() as $locale) {
            self::makeAnyRoute($locale, $anotherPrefix, $controllerAction);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method, $this->getFullUrl($locale, $anotherPrefix, $controllerAction));
                $this->assertSame(
                    200,
                    $response->getStatusCode(),
                    'No OK response for ' . $method . ' on locale ' . $locale . ' route.'
                );
            }
        }
    }

    /** @test */
    public function routingCanWorkWithPrefixAndWithAnotherPrefixAndSomeControllerAction()
    {
        // another prefix
        $anotherPrefix = 'admin';
        $controllerAction = 'someController/someAction';

        foreach (locales() as $locale) {
            self::makeAnyRoute($locale, $anotherPrefix, $controllerAction);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method, $this->getFullUrl($locale, $anotherPrefix, $controllerAction));
                $this->assertSame(
                    200,
                    $response->getStatusCode(),
                    'No OK response for ' . $method . ' on locale ' . $locale . ' route.'
                );
            }
        }
    }
}

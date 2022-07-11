<?php

namespace Dongrim\LaravelLocalization\Tests;

use Illuminate\Support\Facades\Config;
use Dongrim\LaravelLocalization\Middleware\LocalizationMiddleware;

class MiddlewareTest extends TestCase
{
    protected $kernel;
    protected $hideDefaultLocaleInURL;
    protected $defaultLocale;
    protected $sessionLocaleKey;

    public function setUp(): void
    {
        parent::setUp();
        $this->kernel = app('Illuminate\Contracts\Http\Kernel');
        $this->defaultLocale = config('app.locale');
        $this->hideDefaultLocaleInURL = config('localization.hideDefaultLocaleInURL');
        $this->sessionLocaleKey = config('localization.session.locale_key', 'locale');
        session()->forget($this->sessionLocaleKey);

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
    public function ASetApplicationLocaleOnRouteMiddlewareWithoutAnotherPrefix()
    {
        $this->kernel->pushMiddleware(LocalizationMiddleware::class);

        foreach (locales()  as $locale) {
            self::makeAnyRoute($locale);
            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method,  $this->getFullUrl($locale));

                $this->assertEquals(
                    $locale,
                    $response->getContent(),
                    'Current locale: ' . $locale . ' is not equals application locale: ' . app()->getLocale()
                );
            }
        }
    }

    /** @test */
    public function ASetApplicationLocaleOnRouteMiddlewareWithAnotherPrefix()
    {
        $this->kernel->pushMiddleware(LocalizationMiddleware::class);

        $anotherPrefix = 'admin';

        foreach (locales()  as $locale) {
            self::makeAnyRoute($locale, $anotherPrefix);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method,  $this->getFullUrl($locale, $anotherPrefix));

                $this->assertEquals(
                    $locale,
                    $response->getContent(),
                    'Current locale: ' . $locale . ' is not equals application locale: ' . app()->getLocale()
                );
            }
        }
    }


    /** @test */
    public function ASetDefaultLocaleOnApplicationWithLocalizationSessionMiddleware()
    {
        $this->kernel->pushMiddleware(LocalizationMiddleware::class);
        $locale = default_locale();

        self::makeAnyRoute();

        foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
            $response = $this->call($method, $this->getFullUrl());

            $this->assertEquals(
                $locale,
                $response->getContent(),
                'Current locale: ' . $locale . ' is not equals application locale: ' . app()->getLocale()
            );

            $this->assertEquals(
                session($this->sessionLocaleKey),
                $locale,
                'Session locale: ' . session($this->sessionLocaleKey) . ' is not equal current locale: ' . $locale
            );
        }
    }

    /** @test */
    public function ASetApplicationLocaleOnSessionMiddlewareWithAnotherPrefix()
    {
        $this->kernel->pushMiddleware(LocalizationMiddleware::class);
        $anotherPrefix = 'admin';
        $controllerAction = 'someController/someAction';

        foreach (locales()  as $locale) {
            self::makeAnyRoute($locale, $anotherPrefix, $controllerAction);
            session([$this->sessionLocaleKey => $locale]);

            foreach (['get', 'post', 'put', 'patch', 'delete', 'options'] as $method) {
                $response = $this->call($method,  $this->getFullUrl($locale, $anotherPrefix, $controllerAction));

                $this->assertEquals(
                    $locale,
                    $response->getContent(),
                    'Current locale: ' . $locale . ' is not equals application locale: ' . app()->getLocale()
                );
                $this->assertEquals(
                    session($this->sessionLocaleKey),
                    $locale,
                    'Session locale: ' . session($this->sessionLocaleKey) . ' not equal current locale: ' . $locale
                );
            }
        }
    }
}

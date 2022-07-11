<?php

namespace Dongrim\LaravelLocalization;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Dongrim\LaravelLocalization\Drivers\Driver;

class LaravelLocalization
{
    /**
     * Illuminate request class.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Config repository.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $configRepository;

    /**
     * @var string
     */
    protected $appLocale;

    /**
     * Default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var boolean
     */
    protected $useAcceptLanguageHeader;

    /**
     * @var boolean
     */
    protected $hideDefaultLocaleInURL;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var Collection
     */
    protected $locales;

    /**
     * Array locale mapping
     * @var array
     */
    protected $localesMapping;


    public function __construct()
    {
        $this->app = app();
        $this->configRepository = $this->app['config'];
        $this->appLocale = $this->configRepository['app']['locale'] ?? 'en';
        $this->useAcceptLanguageHeader = $this->configRepository['localization']['useAcceptLanguageHeader'] ?? false;
        $this->hideDefaultLocaleInURL = $this->configRepository['localization']['hideDefaultLocaleInURL'] ?? true;
        $this->driver = $this->configRepository['localization']['driver'] ?? 'default';
        $this->localesMapping = $this->configRepository['localization']['localesMapping'] ?? [];
        $this->locales = $this->driver()->getLocales();
        $this->setDefultLocale();
    }


    /**
     * Available locales collection
     * @return Collection
     */
    public function locales(): Collection
    {
        return $this->locales->map(fn ($locale) => $this->localesMapping($locale));
    }


    /**
     * Default locale
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }


    /**
     * Set Prefix localization
     *
     * @param string $anotherPrefix
     *
     * @return string
     */
    public function prefix($anotherPrefix = ''): string
    {
        $anotherPrefix = trim(ltrim($anotherPrefix, '/'));
        $localePrefix = self::getLocalePrefix();

        if (!empty($localePrefix) && !empty($anotherPrefix)) {
            return ltrim($localePrefix . DIRECTORY_SEPARATOR . $anotherPrefix, DIRECTORY_SEPARATOR);
        }

        if (empty($localePrefix) && !empty($anotherPrefix)) {
            return ltrim($anotherPrefix, DIRECTORY_SEPARATOR);
        }

        if (empty($anotherPrefix)) {
            return ltrim($localePrefix, DIRECTORY_SEPARATOR);
        }
    }


    /**
     * @return void
     */
    protected function setDefultLocale(): void
    {
        $locale = null;
        $serverData = request()->server('HTTP_ACCEPT_LANGUAGE') ?? [];

        if ($this->useAcceptLanguageHeader && !blank($serverData)) {
            $serverLocales = collect(preg_split('/,|;/', $serverData));

            $locale = $serverLocales->filter(function ($locale) {
                return $this->locales->contains($locale);
            })->first();
        }

        if (blank($locale)) {
            $locale = $this->appLocale;
        }

        $this->defaultLocale = $this->localesMapping($locale);
    }


    /**
     * Replacing locale names with the name from localesMapping
     *
     * @param string $locales
     *
     * @return string
     */
    public function localesMapping(string $locale): string
    {
        if (!blank($this->localesMapping) && array_key_exists($locale, $this->localesMapping)) {
            $locale = $this->localesMapping[$locale];
        }
        return $locale;
    }


    /**
     *
     * @return string
     */
    public function getLocalePrefix(): string
    {
        $segment = request()->segment(1, '');

        $locales = $this->locales();

        if ($this->hideDefaultLocaleInURL && (bool)$locales->count()) {
            // remove the default language from the collection (so that the urls do not contain the default language)
            if (($key = $locales->search($this->defaultLocale)) !== false) {
                $locales->forget($key);
            }
        }

        if ((bool)$segment && $locales->contains($segment)) {
            return $segment;
        }

        return '';
    }


    /**
     * Get an instance of the set driver.
     *
     * @return Driver
     */
    protected function driver()
    {
        $driver = Str::title($this->driver);

        $class = "Dongrim\LaravelLocalization\Drivers\\" . $driver . "Driver";

        return new $class();
    }
}

<?php

namespace Dongrim\LaravelLocalization\Drivers;

use Illuminate\Support\Collection;

abstract class Driver
{
    protected $config;
    protected $localesMapping;

    public function __construct()
    {
        $this->config = config('localization');
    }

    /**
     * Receipt the collection available locales
     * @return Collection
     */
    public abstract function getLocales(): Collection;
}

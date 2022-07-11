<?php

namespace Dongrim\LaravelLocalization\Facades;

use Illuminate\Support\Facades\Facade;

class Localization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "Localization";
    }
}

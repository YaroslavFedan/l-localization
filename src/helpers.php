<?php

use Dongrim\LaravelLocalization\Facades\Localization;

if (!function_exists('locales')) {
    function locales()
    {
        return Localization::locales();
    }
}

if (!function_exists('default_locale')) {
    function default_locale()
    {
        return Localization::getDefaultLocale();
    }
}

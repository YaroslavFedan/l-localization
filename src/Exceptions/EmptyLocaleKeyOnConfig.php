<?php

namespace Dongrim\LaravelLocalization\Exceptions;

class EmptyLocaleKeyOnConfig extends \Exception
{
    public static function make(): self
    {
        return new static("The field that is responsible for the locale in the database cannot be empty. Please set locale_key in config file localization.php. After then call `artisan config:cache`");
    }
}

<?php

namespace Dongrim\LaravelLocalization\Exceptions;

class EmptyClassNameOnConfig extends \Exception
{
    public static function make(): self
    {
        return new static("The model class responsible for localization cannot be empty. Please set model_namespace in config file localization.php. After then call `artisan config:cache`");
    }
}

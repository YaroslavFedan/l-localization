<?php

namespace Dongrim\LaravelLocalization\Exceptions;

class ModelDoesntHaveColumn extends \Exception
{
    public static function make($class, $column): self
    {
        return new static("Model {$class} doesn't have column '{$column}'");
    }
}

<?php

namespace Dongrim\LaravelLocalization\Exceptions;

class ClassNotImplementable extends \Exception
{
    public static function make($class, $interface): self
    {
        return new static("Model {$class} must by implements interface {$interface}");
    }
}

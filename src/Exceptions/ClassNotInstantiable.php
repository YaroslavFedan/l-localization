<?php

namespace Dongrim\LaravelLocalization\Exceptions;

class ClassNotInstantiable extends \Exception
{
    public static function make($class, $instance = '\Illuminate\Database\Eloquent\Model'): self
    {
        return new static("Model {$class} is not an instance of a class {$instance}");
    }
}

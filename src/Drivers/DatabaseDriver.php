<?php

namespace Dongrim\LaravelLocalization\Drivers;

use Exception;
use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Dongrim\LaravelLocalization\Contracts\Localization;
use Dongrim\LaravelLocalization\Exceptions\ClassNotInstantiable;
use Dongrim\LaravelLocalization\Exceptions\ClassNotImplementable;
use Dongrim\LaravelLocalization\Exceptions\ModelDoesntHaveColumn;
use Dongrim\LaravelLocalization\Exceptions\EmptyClassNameOnConfig;
use Dongrim\LaravelLocalization\Exceptions\EmptyLocaleKeyOnConfig;

class DatabaseDriver extends Driver
{
    public function getLocales(): Collection
    {
        $class = $this->config['database']['model_namespace'];
        $column = $this->config['database']['locale_key'];

        if (empty($class))
            throw EmptyClassNameOnConfig::make();

        if (empty($column))
            throw EmptyLocaleKeyOnConfig::make();

        if (!is_subclass_of($class, 'Illuminate\Database\Eloquent\Model'))
            throw ClassNotInstantiable::make($class);


        $model = new $class();

        if (!Schema::hasColumn($model->getTable(), $column))
            throw ModelDoesntHaveColumn::make(get_class($model), $column);

        return $model->locales()->get()->pluck($column);
    }
}

<?php

namespace Dongrim\LaravelLocalization\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Localizable
{
    public function scopeLocales(Builder $query): Builder
    {
        return $query;
    }
}

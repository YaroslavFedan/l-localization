<?php

namespace Dongrim\LaravelLocalization\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Localization
{
    /**
     * Example scope when returned Eloquent Builder.
     * 
     * 
     * @param Builder $query
     * 
     * @return Builder
     */
    public function scopeLocales(Builder $query): Builder;
}

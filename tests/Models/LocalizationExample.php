<?php

namespace Dongrim\LaravelLocalization\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Dongrim\LaravelLocalization\Traits\Localizable;
use Dongrim\LaravelLocalization\Contracts\Localization;

class LocalizationExample extends Model implements Localization
{
    use Localizable;

    protected $guarded = [];
}

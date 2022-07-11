<?php

namespace Dongrim\LaravelLocalization\Tests\database\seeds;

use Dongrim\LaravelLocalization\Tests\Models\LocalizationExample;

class LocalizationExampleSeeder
{

    public function run($locales = [])
    {
        if (blank($locales))
            return;

        $field = config('localization.database.field') ?? 'locale';

        foreach ($locales as $locale) {
            LocalizationExample::create([$field => $locale]);
        }
    }
}

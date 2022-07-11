<?php

namespace Dongrim\LaravelLocalization\Drivers;

use Illuminate\Support\Collection;

class DefaultDriver extends Driver
{
    public function getLocales(): Collection
    {
        $locales = !blank($this->config['default']['supportedLocales']) ?
            $this->config['default']['supportedLocales'] :
            [];

        return collect(array_keys($locales));
    }
}

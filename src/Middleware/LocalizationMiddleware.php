<?php

namespace Dongrim\LaravelLocalization\Middleware;

use Closure;
use Illuminate\Http\Request;
use Dongrim\LaravelLocalization\Facades\Localization;


class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = config('localization.session.locale_key', 'locale');
        $prefix = Localization::getLocalePrefix();

        if ((bool)$prefix && locales()->contains($prefix))
            $locale = $prefix;
        else
            $locale = default_locale();

        session([$key => $locale]);
        app()->setLocale($locale);

        return $next($request);
    }
}

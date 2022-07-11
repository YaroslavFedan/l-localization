Easy i18n localization for Laravel.

The package offers the following:

- Detect language from browser
- Smart redirects (Save locale in session)
- Smart routing (Define your routes only once, no matter how many languages you use)
- Option to hide default locale in url

## Table of Contents

- <a href="#installation">Installation</a>
- <a href="#register-middleware">Register Middleware</a>
- <a href="#usage">Usage</a>
- <a href="#localization-database">Localization from database</a>
- <a href="#recommended">Recommended</a>
- <a href="#helpers">Helpers</a>
- <a href="#caching-routes">Caching routes</a>


## Laravel compatibility

 Laravel      | laravel-localization
:-------------|:----------
 6.0-9.x (PHP 7 required) | 1.0.x

## Installation

Install the package via composer: `composer require dongrim/laravel-localization`

### Config Files

In order to edit the default configuration you may execute:

```
php artisan vendor:publish --provider="Dongrim\LaravelLocalization\LaravelLocalizationServiceProvider"
```

After that, `config/localization.php` will be created.

The configuration options are:

- **useAcceptLanguageHeader** If true, then automatically detect language from browser.
- **hideDefaultLocaleInURL** If true, then do not show default locale in url.
- **driver** two drivers are supported to access (Default: config file) the list of locales:
  - The configuration file config/localization.php
  - database
- **supportedLocales** Languages of your app (Default: English & Spanish).
- **localesMapping** Rename url locales.

### Register Middleware

You may register the package middleware in the `app/Http/Kernel.php` file:

```php
<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
    * The application's route middleware.
    *
    * @var array
    */
    protected $routeMiddleware = [
        /**** OTHER MIDDLEWARE ****/
        'localize' => \Dongrim\LaravelLocalization\Middleware\LocalizationMiddleware::class,
    ];
}
```

## Usage

Add the following to your routes file:

```php
// routes/web.php

Route::group([
    'prefix' => Localization::prefix(),
    'middleware'=>['localize']
], function(){
	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
	Route::get('/', function(){
		return View::make('welcome');
	})->name('welcome');

    Route::get('test',function(){
		return View::make('test');
	})->name('test');
});

/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/

```

### Localization database

If you want to use the list of locales from the database change config/localization.php .
- specify driver
- set model_namespace
- set locale_key
Then in the model responsible for this list:
- trait Dongrim\LaravelLocalization\Traits\Localizable

For example:
```php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dongrim\LaravelLocalization\Traits\Localizable;
use Dongrim\LaravelLocalization\Contracts\Localization as LocalizationContract;

class Localization extends Model implements LocalizationContract
{
    use Localizable;
}

```

### Recommended

Use named routes for more an easy way to access url localization.
```
<a href="route('welcome')">Welcome</a>
<a href="route('test')">Test</a>
```
A locale other than the default locale will be automatically added to the url
```
// when current locale en and default locale en

    http://site.com/
    http://site.com/test

// when current locale es and default locale en 

    http://site.com/es
    http://site.com/es/test  
```

## Helpers

This package comes with helpers.

### Locales collection

List of all available locales

```
<ul>
    @foreach(locales() as $locale)
     {{ $locale }}
    @endforeach
</ul>
```
### Default locale

When you set useAcceptLanguageHeader = true, to find out the default language you need to use 
```
@dump(default_locale())
```

### Caching routes

To cache your routes, use:

``` bash
php artisan route:clear
```


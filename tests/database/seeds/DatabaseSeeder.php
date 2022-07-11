<?php

namespace Dongrim\LaravelLocalization\Tests\database\seeds;

use Illuminate\Database\Seeder;
use Dongrim\LaravelLocalization\Tests\database\seeds\LocalizationExampleSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(LocalizationExampleSeeder::class);
    }
}

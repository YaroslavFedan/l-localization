<?php

namespace Dongrim\LaravelLocalization\Tests\database\seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(LocalizationExampleSeeder::class);
    }
}

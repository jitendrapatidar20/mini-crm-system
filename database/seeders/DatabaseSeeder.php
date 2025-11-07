<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        $this->call([
            CustomFieldSeeder::class,
            ContactSeeder::class,
            AdminSeeder::class,
            SettingsTableSeeder::class,
            PageTitlesTableSeeder::class,
        ]);
    }
}

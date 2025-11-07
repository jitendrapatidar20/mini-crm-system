<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PageTitlesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('page_titles')->truncate(); 

        DB::table('page_titles')->insert([
            [
                'id' => 1,
                'slug' => 'login',
                'title' => 'Login',
                'meta_title' => 'Login',
                'meta_keyword' => 'Login',
                'meta_description' => 'Login',
                'created_at' => Carbon::parse('2025-11-07 19:38:17'),
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'slug' => 'Contact List',
                'title' => 'Contact List',
                'meta_title' => 'Contact List',
                'meta_keyword' => 'Contact List',
                'meta_description' => 'Contact List',
                'created_at' => Carbon::parse('2025-11-07 19:39:01'),
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'slug' => 'Dashboard',
                'title' => 'Dashboard',
                'meta_title' => 'Dashboard',
                'meta_keyword' => 'Dashboard',
                'meta_description' => 'Dashboard',
                'created_at' => Carbon::parse('2025-11-07 12:05:02'),
                'updated_at' => null,
            ],
            [
                'id' => 4,
                'slug' => 'Settings',
                'title' => 'Settings',
                'meta_title' => 'Settings',
                'meta_keyword' => 'Settings',
                'meta_description' => 'Settings',
                'created_at' => Carbon::parse('2025-11-07 14:06:44'),
                'updated_at' => null,
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->truncate(); // optional: clear old data

        DB::table('settings')->insert([
            [
                'id' => 1,
                'name' => 'PROJECT_NAME',
                'slug' => 'project-name',
                'description' => 'Mini CRM',
                'parameter_type' => 'string',
                'status' => 1,
                'created_at' => Carbon::parse('2017-12-09 04:00:00'),
                'updated_at' => Carbon::parse('2025-11-07 07:30:08'),
            ],
            [
                'id' => 3,
                'name' => 'CONTACT_US_INQUIRY_EMAIL',
                'slug' => 'contact-us-inquiry-email',
                'description' => 'jitendra.patidar20@gmail.com',
                'parameter_type' => 'email',
                'status' => 1,
                'created_at' => null,
                'updated_at' => Carbon::parse('2020-09-15 11:11:31'),
            ],
            [
                'id' => 4,
                'name' => 'SOCIAL_FACEBOOK_URL',
                'slug' => 'social-facebook-url',
                'description' => '',
                'parameter_type' => 'url',
                'status' => 1,
                'created_at' => Carbon::parse('2019-06-22 04:00:00'),
                'updated_at' => Carbon::parse('2020-12-11 08:42:52'),
            ],
            [
                'id' => 5,
                'name' => 'SOCIAL_TWITTER_URL',
                'slug' => 'social-twitter-url',
                'description' => '',
                'parameter_type' => 'url',
                'status' => 1,
                'created_at' => Carbon::parse('2019-06-22 04:00:00'),
                'updated_at' => Carbon::parse('2020-12-11 08:43:14'),
            ],
            [
                'id' => 6,
                'name' => 'SOCIAL_LINKEDIN_URL',
                'slug' => 'social-linkedin-url',
                'description' => '',
                'parameter_type' => 'url',
                'status' => 1,
                'created_at' => Carbon::parse('2019-06-22 04:00:00'),
                'updated_at' => Carbon::parse('2021-03-03 12:18:52'),
            ],
            [
                'id' => 7,
                'name' => 'SOCIAL_WHATAPPS',
                'slug' => 'social-whatapps',
                'description' => '+91-9929167751',
                'parameter_type' => 'string',
                'status' => 1,
                'created_at' => Carbon::parse('2019-06-22 04:00:00'),
                'updated_at' => Carbon::parse('2022-01-28 08:58:11'),
            ],
            [
                'id' => 8,
                'name' => 'COPYRIGHT',
                'slug' => 'copyright',
                'description' => '© 2025 MiniCRM. All Rights Reserved.',
                'parameter_type' => 'string',
                'status' => 1,
                'created_at' => null,
                'updated_at' => Carbon::parse('2025-11-07 08:41:09'),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = [
            'phone_number' => "+72 0123 11212",
            'email' => "example@example.com",
            'currency' => "USD",
            'social_links' => '{"links":{"twitter":"Nostrud provident q","youtube":"asdfasdfasdf","facebook":"Est necessitatibus i","instagram":"Et ducimus est rem"}}',
        ];

        Settings::create($setting);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Driver;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     LaratrustSeeder::class,


        // ]);
        // \App\Models\User::factory(10)->create();


        // \App\Models\User::create([
        //     'first_name' => 'Test User',
        //     'last_name' => 'Test User',
        //     'password' => Hash::make("password"),
        //     'email' => 'admin@admin.com',
        //     'mobile_number' => '01285323276',
        // ]);

        // $this->call([
        //     pagesSeeder::class,
        //     SettingsSeeder::class
        // ]);


        $driver =  Driver::first();

       for ($i = 0; $i < 200000; $i++) {
           $driver->reviews()->create([
               'tourist_id' => 3,
               'comment' => "This Gude is good",
               'tourist_rating' => 5,
           ]);
            $driver->total_rating = ($driver->reviews()->avg('tourist_rating') + $driver->admin_rating) / 2;
            $driver->save();
       }
    }
}

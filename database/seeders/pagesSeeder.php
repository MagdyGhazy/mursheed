<?php

namespace Database\Seeders;

use App\Models\Pages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class pagesSeeder extends Seeder
{
    /*
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = new Pages(); // This is an Eloquent model
        $pages
            ->setTranslation('title', 'gb', 'About Us')
            ->setTranslation('title', 'az', 'About Us')
            ->setTranslation('title', 'ge', 'About Us')
            ->setTranslation('title', 'ru', 'About Us')
            ->setTranslation('title', 'sa', 'About Us')
            ->setTranslation('title', 'tr', 'About Us')

            ->setTranslation('description', 'gb', 'any thing')
            ->setTranslation('description', 'az', 'any thing')
            ->setTranslation('description', 'ge', 'any thing')
            ->setTranslation('description', 'ru', 'any thing')
            ->setTranslation('description', 'sa', 'any thing')
            ->setTranslation('description', 'tr', 'any thing')
            ->save();


        $pages2 = new Pages(); // This is an Eloquent model
        $pages2
            ->setTranslation('title', 'gb', 'terms and condtion')
            ->setTranslation('title', 'az', 'terms and condtion')
            ->setTranslation('title', 'ge', 'terms and condtion')
            ->setTranslation('title', 'ru', 'terms and condtion')
            ->setTranslation('title', 'sa', 'terms and condtion')
            ->setTranslation('title', 'tr', 'terms and condtion')

            ->setTranslation('description', 'gb', 'any thing')
            ->setTranslation('description', 'az', 'any thing')
            ->setTranslation('description', 'ge', 'any thing')
            ->setTranslation('description', 'ru', 'any thing')
            ->setTranslation('description', 'sa', 'any thing')
            ->setTranslation('description', 'tr', 'any thing')
            ->save();
    }
}

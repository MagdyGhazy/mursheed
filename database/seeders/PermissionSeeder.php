<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view clients']);
        Permission::create(['name' => 'create clients']);
        Permission::create(['name' => 'edit clients']);
        Permission::create(['name' => 'delete clients']);

        Permission::create(['name' => 'view guides']);
        Permission::create(['name' => 'create guides']);
        Permission::create(['name' => 'edit guides']);
        Permission::create(['name' => 'delete guides']);

        Permission::create(['name' => 'view drivers']);
        Permission::create(['name' => 'create drivers']);
        Permission::create(['name' => 'edit drivers']);
        Permission::create(['name' => 'delete drivers']);

        
        Permission::create(['name' => 'view tourist']);
        Permission::create(['name' => 'create tourist']);
        Permission::create(['name' => 'edit tourist']);
        Permission::create(['name' => 'delete tourist']);

        
        Permission::create(['name' => 'Tourism Management']);

        Permission::create(['name' => 'view Accommodition']);
        Permission::create(['name' => 'create Accommodition']);
        Permission::create(['name' => 'edit Accommodition']);
        Permission::create(['name' => 'delete Accommodition']);

      

        Permission::create(['name' => 'view Flight Reservations']);
        Permission::create(['name' => 'create Flight Reservations']);
        Permission::create(['name' => 'edit Flight Reservations']);
        Permission::create(['name' => 'delete Flight Reservations']);

        Permission::create(['name' => 'view Offers']);
        Permission::create(['name' => 'create Offers']);
        Permission::create(['name' => 'edit Offers']);
        Permission::create(['name' => 'delete Offers']);

        Permission::create(['name' => 'view attracives']);
        Permission::create(['name' => 'create attracives']);
        Permission::create(['name' => 'edit attracives']);
        Permission::create(['name' => 'delete attracives']);

     
        Permission::create(['name' => 'view settings']);
        Permission::create(['name' => 'create settings']);
        Permission::create(['name' => 'edit settings']);
        Permission::create(['name' => 'delete settings']);

        
        Permission::create(['name' => 'view Pages']);
        Permission::create(['name' => 'create Pages']);
        Permission::create(['name' => 'edit Pages']);
        Permission::create(['name' => 'delete Pages']);

        
        Permission::create(['name' => 'view terms']);
        Permission::create(['name' => 'create terms']);
        Permission::create(['name' => 'edit terms']);
        Permission::create(['name' => 'delete terms']);

            
        Permission::create(['name' => 'view contact']);
        Permission::create(['name' => 'create contact']);
        Permission::create(['name' => 'edit contact']);
        Permission::create(['name' => 'delete contact']);

           
        Permission::create(['name' => 'view banner']);
        Permission::create(['name' => 'create banner']);
        Permission::create(['name' => 'edit banner']);
        Permission::create(['name' => 'delete banner']);
         
        Permission::create(['name' => 'view country']);
        Permission::create(['name' => 'create country']);
        Permission::create(['name' => 'edit country']);
        Permission::create(['name' => 'delete country']);

        Permission::create(['name' => 'view reminder']);
        Permission::create(['name' => 'create reminder']);
        Permission::create(['name' => 'edit reminder']);
        Permission::create(['name' => 'delete reminder']);
        
         
        Permission::create(['name' => 'view order list']);
        Permission::create(['name' => 'create order list']);
        Permission::create(['name' => 'edit order list']);
        Permission::create(['name' => 'delete order list']);

        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'create reports']);
        Permission::create(['name' => 'edit reports']);
        Permission::create(['name' => 'delete reports']);

        
        
        Permission::create(['name' => 'view payment reports ']);
        Permission::create(['name' => 'create payment reports']);
        Permission::create(['name' => 'edit payment reports']);
        Permission::create(['name' => 'delete payment reports']);

        
        
         
        
        


    }
}

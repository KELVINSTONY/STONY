<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleAndPermissionSeeder extends Seeder
{
    /**

     * 
     * 
     * 
     * Run the database seeds.
     */
   
        public function run()
    {
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);
        Permission::create(['name' => 'create-patients']);
        Permission::create(['name' => 'edit-patients']);
        Permission::create(['name' => 'delete-patients']);

        Permission::create(['name' => 'view-masters']);
        Permission::create(['name' => 'view-reports']);
        Permission::create(['name' => 'view-billing']);
        Permission::create(['name' => 'view-expenses']);
        Permission::create(['name' => 'view-appointment']);
        Permission::create(['name' => 'view-stores']);
        Permission::create(['name' => 'view-dashboard']);


        Permission::create(['name' => 'create-medicine']);
        Permission::create(['name' => 'edit-medicine']);
        Permission::create(['name' => 'delete-medicine']);

        $adminRole = Role::create(['name' => 'Admin']);
        $salesRole = Role::create(['name' => 'Sales']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'create-patients',
            'edit-patients',
            'delete-patients',
            'create-medicine',
            'edit-medicine',
            'delete-medicine',
        ]);

        $salesRole->givePermissionTo([
            'create-medicine',
            'edit-medicine',
            'delete-medicine',
        ]);
    }
    
}

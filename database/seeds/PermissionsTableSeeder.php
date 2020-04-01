<?php

use Illuminate\Database\Seeder;
//use Spatie\Permission\Permission;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::updateOrCreate(['name'=>'access-admin-ui']);
        Permission::updateOrCreate(['name'=>'manage-site-settings']);
        Permission::updateOrCreate(['name'=>'manage-users']);
        Permission::updateOrCreate(['name'=>'manage-pages']);
        Permission::updateOrCreate(['name'=>'manage-media']);
        Permission::updateOrCreate(['name'=>'manage-logs']);
 
    }
}

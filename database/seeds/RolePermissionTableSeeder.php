<?php

use Illuminate\Database\Seeder;
use App\Models\Permissions;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('role_has_permissions')->delete();

        \DB::table('role_has_permissions')->insert([
            // Admin - should have all
            [
                'permission_id' => 1,
                'role_id'       => 1
            ],
            [
                'permission_id' => 2,
                'role_id'       => 1
            ],
            [
                'permission_id' => 3,
                'role_id'       => 1
            ],
            [
                'permission_id' => 4,
                'role_id'       => 1
            ],
            [
                'permission_id' => 5,
                'role_id'       => 1
            ],
            [
                'permission_id' => 6,
                'role_id'       => 1
            ],
            // Editor - access admin, manage users, manage pages, manage media
            [
                'permission_id' => 1,
                'role_id'       => 2
            ],
            [
                'permission_id' => 2,
                'role_id'       => 2
            ],
            [
                'permission_id' => 4,
                'role_id'       => 2
            ]
            
        ]);
 
    }
}

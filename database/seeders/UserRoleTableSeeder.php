<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissions;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('model_has_roles')->delete();

        \DB::table('model_has_roles')->insert([
            [
                'role_id'       => 1,
                'model_type'    => 'App\Models\User',
                'model_id'       => 1
            ]            
        ]);
 
    }
}

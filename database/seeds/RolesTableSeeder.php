<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('roles')->delete();

        \DB::table('roles')->insert([
            0 => [
                'id'         => 1,
                'name'  => 'Administrator',
                'guard_name' => 'web',
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id'         => 2,
                'name'  => 'Editor',
                'guard_name' => 'web',
                'created_at' => null,
                'updated_at' => null,
            ],
            2 => [
                'id'         => 3,
                'name'  => 'User',
                'guard_name' => 'web',
                'created_at' => null,
                'updated_at' => null,
            ]
        ]);
    }
}

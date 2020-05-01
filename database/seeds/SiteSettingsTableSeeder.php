<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('options')->delete();

        \DB::table('options')->insert([
            [
                'key'   =>  'version',
                'value' =>  '0.1'
            ],
            [
                'key'   =>  'site_name',
                'value' =>  'Canvas'
            ],
            [
                'key'   =>  'site_description',
                'value' =>  'Canvas is an open source CMS starter kit built with Laravel. It provides a starting point for building an advanced website or your own content management system.'
            ],
            [
                'key'   =>  'from_name',
                'value' =>  'Canvas Admin'
            ],
            [
                'key'   =>  'from_email',
                'value' =>  'admin@admin.com'
            ],
            [
                'key'   =>  'notification_email',
                'value' =>  'noreply@email.com'
            ],
            [
                'key'   =>  'maintenance_mode',
                'value' =>  0
            ],
            [
                'key'   =>  'google_analytics_id',
                'value' =>  ''
            ],
            [
                'key'   =>  'modules',
                'value' =>  ''
            ],
            [
                'key'   =>  'business_name',
                'value' =>  'ACME, Inc.'
            ],
            [
                'key'   =>  'business_email',
                'value' =>  ''
            ],
            [
                'key'   =>  'business_phone',
                'value' =>  '123-456-7890'
            ],
            [
                'key'   =>  'business_address',
                'value' =>  '123 Sesame St'
            ],
            [
                'key'   =>  'default_role',
                'value' =>  '3'
            ],
            [
                'key'   =>  'resource_rename',
                'value' =>  '[]'
            ]
        ]);
    }
}

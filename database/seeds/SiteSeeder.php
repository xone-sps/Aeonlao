<?php

use Illuminate\Database\Seeder;
use App\Models\Site;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::create(['key' => 'site_name', 'value' => 'Lao Education Quality Assurance Collection and Management Website.']);

        Site::create(['key' => 'address', 'value' => 'Ministry of Education and Sports, Street Lekyheuang, Ban Anou, District Chanthabouly, Vientiane Laos PDR.']);

        Site::create(['key' => 'email', 'value' => 'xone.sps@gmail.com']);

        Site::create(['key' => 'email_logo', 'value' => 'email_logo.png']);

        Site::create(['key' => 'fav_icon', 'value' => 'favicon.png']);

        Site::create(['key' => 'website_logo', 'value' => 'logo.png']);

        Site::create(['key' => 'fresh_version', 'value' => '?vc95c3b0892a8c650a95b4e2bf4cb1fa9']);
    }
}

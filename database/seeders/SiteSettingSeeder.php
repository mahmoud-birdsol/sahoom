<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name',        'value' => 'SAHOOME',                                                                     'group' => 'general'],
            ['key' => 'site_description', 'value' => 'It Refers To The Practice Of Sharing Access To Real Estate (Such As Homes, Offices, Or Tourist Rentals).', 'group' => 'general'],
            ['key' => 'contact_email',    'value' => 'Sahoome@gmail.com',                                                           'group' => 'contact'],
            ['key' => 'contact_phone',    'value' => '+966-554-648',                                                                 'group' => 'contact'],
            ['key' => 'contact_address',  'value' => '321 Market St, Los Angeles, CA.',                                             'group' => 'contact'],
            ['key' => 'copyright',        'value' => 'Al Copywrite Reserved For Sahoome@2025',                                      'group' => 'general'],
            ['key' => 'facebook_url',     'value' => '#',                                                                            'group' => 'social'],
            ['key' => 'instagram_url',    'value' => '#',                                                                            'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            \App\Models\SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

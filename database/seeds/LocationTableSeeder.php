<?php

use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert 1 record into location table
        DB::table('locations')->insert([
            'name' => 'University of Canberra, Building 25',
            'address' => 'Building 25, University of Canberra Pantowora St, Bruce ACT 2617, Australia',
            'latitude' => '-35.23811',
            'longitude' => '149.082363'
        ]);
    }
}

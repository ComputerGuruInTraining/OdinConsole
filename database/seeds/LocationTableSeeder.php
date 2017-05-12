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
//insert single location
//      Bank of America Financial Center 30 Smith Ranch Rd, San Rafael, CA 94903, USA
//      Latitude: 38.019522 | Longitude: -122.535801
        DB::table('locations')->insert([
            'name' => 'Bank of America',
            'address' => 'Bank of America Financial Center 30 Smith Ranch Rd, San Rafael, CA 94903, USA',
            'latitude' => '38.019522',
            'longitude' => '-122.535801'
        ]);

//insert 3 University of San Francisco Locations

//      The Market Cafe 2130 Fulton St, San Francisco, CA 94117, USA
//      Latitude: 37.776565 | Longitude: -122.450261
        DB::table('locations')->insert([
            'name' => 'Market Cafe, USF',
            'address' => 'The Market Cafe 2130 Fulton St, San Francisco, CA 94117, USA',
            'latitude' => '37.776565',
            'longitude' => '-122.450261'
        ]);

//      John Lo Schiavo, S.J. Center For Science And Innovation University Center, 2130 Fulton St, San Francisco, CA 94117, USA
//      Latitude: 37.776247 | Longitude: -122.45111
        DB::table('locations')->insert([
            'name' => 'John Lo Schiavo, USF',
            'address' => 'John Lo Schiavo, University Center, 2130 Fulton St, San Francisco, CA 94117, USA',
            'latitude' => '37.776247',
            'longitude' => '-122.45111'
        ]);


//      McLaren Conference Center, San Francisco, CA 94117, USA
//      Latitude: 37.776004 | Longitude: -122.449886
        DB::table('locations')->insert([
            'name' => 'McLaren Conference Center, USF',
            'address' => 'McLaren Conference Center, San Francisco, CA 94117, USA',
            'latitude' => '37.776004',
            'longitude' => '-122.449886'
        ]);

//several locations, not closely situated
//      The Castro Theatre 429 Castro St, San Francisco, CA 94114, USA
//      Latitude: 37.762033 | Longitude: -122.434759
        DB::table('locations')->insert([
            'name' => 'Castro Theatre',
            'address' => 'Castro Theatre 429 Castro St, San Francisco, CA 94114, USA',
            'latitude' => '37.762033',
            'longitude' => '-122.434759'
        ]);
//      Union Square, San Francisco, CA 94108, USA
//      Latitude: 37.787994 | Longitude: -122.407437
        DB::table('locations')->insert([
            'name' => 'Union Square',
            'address' => 'Union Square, San Francisco, CA 94108, USA',
            'latitude' => '37.787994',
            'longitude' => '-122.407437'
        ]);
//      South of Market, San Francisco, CA, USA
//      Latitude: 37.778519 | Longitude: -122.405639
        DB::table('locations')->insert([
            'name' => 'South of Market',
            'address' => 'South of Market, San Francisco, CA, USA',
            'latitude' => '37.778519',
            'longitude' => '-122.405639'
        ]);

//      Ferry Building Marketplace San Francisco Ferry Bldg, 1 Sausalito - San Francisco Ferry Bldg, San Francisco, CA 94111, USA
//      Latitude: 37.795274 | Longitude: -122.393421
        DB::table('locations')->insert([
            'name' => 'Ferry Building Marketplace',
            'address' => 'Ferry Building Marketplace, 1 Sausalito - San Francisco Ferry Bldg, San Francisco, CA 94111, USA',
            'latitude' => '37.795274',
            'longitude' => '-122.393421'
        ]);
    }
}

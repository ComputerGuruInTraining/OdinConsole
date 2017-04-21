<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$vader = DB::table('users')->insert([
				'name'   => 'doctorv',
				'email'      => 'darthv@deathstar.com',
				'password'   => Hash::make('thedarkside'),
//				'first_name' => 'Darth',
//				'last_name'  => 'Vader',
                'remember_token' => str_random(100),
				'created_at' => new DateTime(),
				'updated_at' => new DateTime()
			]);

		DB::table('users')->insert([
				'name'   => 'goodsidesoldier',
				'email'      => 'lightwalker@rebels.com',
				'password'   => Hash::make('hesnotmydad'),
//				'first_name' => 'Luke',
//				'last_name'  => 'Skywalker',
                'remember_token' => str_random(100),
				'created_at' => new DateTime(),
				'updated_at' => new DateTime()
			]);

		DB::table('users')->insert([
				'name'   => 'greendemon',
				'email'      => 'dancingsmallman@rebels.com',
				'password'   => Hash::make('yodaIam'),
//				'first_name' => 'Yoda',
//				'last_name'  => 'Unknown',
                'remember_token' => str_random(100),
				'created_at' => new DateTime(),
				'updated_at' => new DateTime()
			]);
	}

}
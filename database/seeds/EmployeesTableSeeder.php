<?php

use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('employees')->insert([
           'first_name' => str_random(10),
           'last_name' => str_random(10),
           'gender' => 'M',
           'mobile' => '0400000000',
           'dob' => '1987-01-01',
           'address' => 'University of Canberra, Bruce',
           'email' => 'test@test.com',
           'password' => bcrypt('test')
       ]);
    }
}

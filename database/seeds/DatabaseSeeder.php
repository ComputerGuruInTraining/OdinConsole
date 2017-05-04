<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /*
    * TODO: Of course, you should add the deleted_at column to your database table. The Laravel schema builder contains a helper method to create this column:
    * Schema::table('flights', function ($table) {
    *     $table->softDeletes();
    * });
    * reference: https://laravel.com/docs/5.4/eloquent#querying-soft-deleted-models
    */

    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(EmployeesTableSeeder::class);
    }
}

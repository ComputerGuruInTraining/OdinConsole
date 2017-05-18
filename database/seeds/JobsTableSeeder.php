<?php

use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->insert([
            'company_id' => 1,//no company table at time of seeding jobs table
            'manager_id' => 1,//no manager table at time of seeding jobs table
            'client_id' => 1,//no client table at time of seeding jobs table
            'assigned_user_id' => 1,//3 users
            'job_scheduled_for' => date("2017-05-02 10:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'Bank of America'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 2,
            'job_scheduled_for' => date("2017-05-01 15:00:00"),
            'estimated_job_duration' => 6,
            'locations' => 'Bank of America'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 3,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,//until 2am 2017-05-04
            'locations' => 'Castro Theatre'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 3,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'Union Square'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 3,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'South of Market'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 3,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'Ferry Building Marketplace'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 1,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,//until 2am 2017-05-04
            'locations' => 'Castro Theatre'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 1,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'Union Square'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 3,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'South of Market'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 1,
            'assigned_user_id' => 1,
            'job_scheduled_for' => date("2017-05-03 18:00:00"),
            'estimated_job_duration' => 8,
            'locations' => 'Ferry Building Marketplace'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 2,
            'assigned_user_id' => 1,
            'job_scheduled_for' => date("2017-05-04 9:00:00"),
            'estimated_job_duration' => 9,
            'locations' => 'McLaren Conference Center, USF'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 2,
            'assigned_user_id' => 2,
            'job_scheduled_for' => date("2017-05-04 9:00:00"),
            'estimated_job_duration' => 9,
            'locations' => 'John Lo Schiavo, USF'//location_name
        ]);

        DB::table('jobs')->insert([
            'company_id' => 1,
            'manager_id' => 1,
            'client_id' => 2,//TODO: consider need for client_id in our app
            'assigned_user_id' => 2,
            'job_scheduled_for' => date("2017-05-04 9:00:00"),
            'estimated_job_duration' => 9,//TODO: consider using a double, but integer suffices. Just not 100% accurate
            'locations' => 'Market Cafe, USF'//location_name//TODO: consider a new table job_locations to optimize db design
        ]);


    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function($column){
            $column->dropColumn('job_scheduled_end');
            $column->renameColumn('job_scheduled_start', 'job_scheduled_for');
            $column->integer('estimated_job_duration');
            $column->string('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function($column){
            $column->datetime('job_scheduled_end');
            $column->renameColumn('job_scheduled_for','job_scheduled_start');
            $column->dropColumn('estimated_job_duration');
            $column->dropColumn('locations');
        });
    }
}

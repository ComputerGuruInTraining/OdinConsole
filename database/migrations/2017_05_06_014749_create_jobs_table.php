<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('company_id');

            $table->integer('manager_id')->nullable();

            $table->integer('client_id')->nullable();

            $table->integer('assigned_user_id');

            $table->datetime('job_scheduled_start');

            $table->datetime('job_scheduled_end');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('employees', function(Blueprint $table){
               $table->increments('id');
               $table->text('first_name');
               $table->text('last_name');
               $table->string('gender',1)->nullable();
               $table->integer('mobile');
               $table->date('dob');
               $table->text('address')->nullable();
               $table->text('email');
               $table->text('password');
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
        //
        Schema::drop('employees');
    }
}

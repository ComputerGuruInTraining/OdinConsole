<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLocationsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function(Blueprint $column){
//            $column->string('name', 50)->unique()->change();
            $column->string('address', 150)->unique()->change();
            $column->string('longitude', 20)->unique()->change();
            $column->string('latitude', 20)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($column){

            $column->string('address', 150)->change();
            $column->string('address', 20)->change();
            $column->string('longitude', 20)->change();
        });
    }
}

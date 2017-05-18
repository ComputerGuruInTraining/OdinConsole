<?php

namespace App\Models;
use Carbon\Carbon;
use Eloquent;

use Illuminate\Database\Eloquent\Model;

class Employee extends Eloquent
{
    //
   protected $table= "employees";
   protected $dates = ['dob'];

   public function setDobAttribute($dob){

       $this->attributes['dob']  = Carbon::parse($dob);
   }

   public function getDobAttribute($dob){
       return Carbon::createFromFormat('Y-m-d', $dob)->format('d M Y');
   }
}

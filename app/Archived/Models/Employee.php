<?php

namespace App\Models;
use Carbon\Carbon;
use Eloquent;

use Illuminate\Database\Eloquent\Model;

class Employee extends Eloquent
{
    //
   protected $table= "employees";
//   protected $dates = ['dob'];
////
//   public function setDobAttribute($dob){
//
//       $this->attributes['dob']  = Carbon::createFromFormat('d/m/Y', $dob)->format('Y-m-d');
//   }
//
//   public function getDobAttribute($dob){
//       return Carbon::createFromFormat('Y-m-d', $dob)->format('d M Y');
//   }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

use Illuminate\Routing\Controller as BaseController;
class EmployeeController extends BaseController {

  public function showEmployee()
	{
		return view('home/employee/employees');
	}

}

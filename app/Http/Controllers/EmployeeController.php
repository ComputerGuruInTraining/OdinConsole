<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Employee;
use Input;
use Hash;
use Carbon\Carbon;
use Session;
use Redirect;
use GuzzleHttp;

use App\Utlities\ApiAuth;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->get('http://odinlite.com/public/api/employees/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $employees = json_decode((string)$response->getBody());
                return view('employee.employees', compact('employees'));
//                return view ('employee.employees', compact('employees'));

//                return view('user/index')->with(array('users' => $users, 'url' => 'user'));

            }
            else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return view('admin_template');
        }
        catch (\ErrorException $error) {
            echo $error;
            return Redirect::to('/login');
        }

//      $employees = Employee::all();
//      return view('employee.employees', compact('employees'));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         return view('employee.add-employee');
        // return view('home/employee/add-employee');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = new Employee;
        $employee->first_name = Input::get('first_name');
        $employee->last_name = Input::get('last_name');
        // $employee->dob = Input::get('dob');Carbon::parse($request->datepicker);
        $employee->dob = Input::get('dateOfBirth');
//        $employee->dob= Carbon::parse($request->datepicker);
        $employee->gender = Input::get('sex');
        $employee->mobile = Input::get('mobile');
        $employee->email = Input::get('email');
        $employee->password=  Hash::make(Input::get('password'));

        $employee->save();
        // redirect('employees');
        $employees = Employee::latest()->get();
        return view('employee.employees',compact('employees'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //shows the detail of a particular employee when accessed from /employees page
        return view('employee.show', compact('employee'));
        // $employee = Employee::find($id);
        // return view('home/employee/edit-employee/{employee}')->with('employee' -> $employee));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        return view('employee.edit-employee', compact('employee'));
        // $employee = Employee::find($id);
        // return View::make('employees.form', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
        // $employee = Employee::find($id);

        //store the data in the db
        $employee->first_name = Input::get('first_name');
        $employee->last_name = Input::get('last_name');
        $employee->dob = Input::get('dateOfBirth');
//         Carbon::parse($request->datepicker);

//        $employee->dob= Carbon::parse($request->datepicker);
//        $employee->dob=Carbon::createFromFormat('d/m/yyyy', $request->input('dob'))->format('Y-m-d');

        $employee->gender = Input::get('sex');
        $employee->mobile = Input::get('mobile');
        $employee->email = Input::get('email');
        $employee->password=  Hash::make(Input::get('password'));
        $employee->save();

        //redirect to employees listing page after updating an employee detail
        return Redirect::to('/employees');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee = Employee::find($employee->id);
        $employee->delete();
//        Session::flash('flash_message','successfully deleted.');
        return redirect()->route("employees.index");
    }
}

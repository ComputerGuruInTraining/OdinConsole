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
//                dd($employees);
//                return view('employee.employees', compact('employees'));
//                return view ('employee.employees', compact('employees'));

                return view('employee/employees')->with(array('employees' => $employees, 'url' => 'employee'));

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
        if (session()->has('token')) {
            return view('employee/add-employee');
        }
        else {
            return Redirect::to('/login');
        }
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
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

//            //gather data from input fields
                $this->validate($request, [
                    'dateOfBirth' => 'required',
                ]);

                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                // $employee->dob = Input::get('dob');Carbon::parse($request->datepicker);
                $dob = Input::get('dateOfBirth');
//        $employee->dob= Carbon::parse($request->datepicker);
                $gender = Input::get('sex');
                $mobile = Input::get('mobile');
                $email = Input::get('email');
                $password=  Hash::make(Input::get('password'));
                $dob = jobDateTime($dob, "00:00");

//                dd($first_name,$last_name,$dob,$gender,$mobile,$email,$password,$client,$compId);
                $response = $client->post('http://odinlite.com/public/api/employees', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'dateOfBirth' => $dob, 'sex' => $gender,
                            'mobile' => $mobile,'email'=>$email,'password'=>$password, 'company_id' => $compId
                        )
                    )
                );
                $employee = json_decode((string)$response->getBody());
//                dd($employee);
                //display confirmation page
//                return view('confirm-create')->with(array('theData' => $name, 'url' => 'locations', 'entity' => 'Location'));
                return Redirect::to('/employees');
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/employees');
        }
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

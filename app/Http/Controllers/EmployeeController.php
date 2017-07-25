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
                    'dateOfBirth' => 'required'
                ]);

                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                // $employee->dob = Input::get('dob');Carbon::parse($request->datepicker);
                $dob = Input::get('dateOfBirth');
//        $employee->dob= Carbon::parse($request->datepicker);
                $gender = Input::get('sex');
                $mobile = Input::get('mobile');
                $email = Input::get('email');
                $password=  Hash::make(str_random(8));
                $dob = Carbon::createFromFormat('d/m/Y', $dob)->format('Y-m-d');

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
//              dd($employee);
                //display confirmation page
//                return view('confirm-create')->with(array('theData' => $name, 'url' => 'locations', 'entity' => 'Location'));
                $theAction = 'You have successfully created a new employee. Please advise the employee to check their junk email folder for the email';

                return view('confirm')->with(array('theAction' => $theAction));
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
    public function edit($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;


                $response = $client->get('http://odinlite.com/public/api/employees/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $employees = json_decode((string)$response->getBody());
//                dd($employees);
                return view('employee/edit-employee')->with('employees', $employees);

            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            $err = 'Please provide a valid address and ensure the address is not already stored in the database.';
            $errors = collect($err);
            return view('user/create')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
            $e = 'Please fill in all required fields';
            $errors = collect($e);
            return view('user/create')->with('errors', $errors);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                //get the data from the form
                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                $dob = Input::get('dateOfBirth');
                $dob = $this->formatDob($dob);
//        $employee->dob= Carbon::parse($request->datepicker);
//        $employee->dob=Carbon::createFromFormat('d/m/yyyy', $request->input('dob'))->format('Y-m-d');

                $gender = Input::get('sex');
                $mobile = Input::get('mobile');
                $email = Input::get('email');
              //  $password=  Hash::make(Input::get('password'));


                $client = new GuzzleHttp\Client;

//                dd($first_name,$last_name,$dob,$gender,$mobile,$email,$password,$client);

                $response = $client->put('http://odinlite.com/public/api/employees/'.$id.'/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'email' => $email, 'dateOfBirth'=> $dob, 'sex'=>$gender, 'mobile'=> $mobile
                        )
                    )
                );

                $employee = json_decode((string)$response->getBody());
//dd($employee);
                //direct user based on whether record updated successfully or not
                if($employee->success == true)
                {
                    return redirect()->route('employees.index');
                }
                else{
                    return redirect()->route("employees.edit");
                }
            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Please provide valid changes';
            $errors = collect($err);
            echo($err);
            return Redirect::to('/locations');
        }

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

    public function formatDob($dob){

        $newDob = Carbon::createFromFormat('d/m/Y', $dob)->format('Y-m-d');
        return $newDob;

    }
}

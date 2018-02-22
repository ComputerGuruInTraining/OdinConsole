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
use DateTime;
use Config;

//todo: necessary??
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\View;

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

                $response = $client->get(Config::get('constants.API_URL') . 'employees/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $employees = json_decode((string)$response->getBody());
                //format dates to be mm/dd/yyyy for case notes
                foreach ($employees as $i => $item) {
                    //convert string date to DateTime and format date
                    $t = $employees[$i]->dob;

                    $dt = new DateTime($t);
                    $date = $dt->format('m/d/Y');

                    //add formattedDate to $employees object
                    $employees[$i]->dateBirth = $date;
                }

                $employees = array_sort($employees, 'last_name', SORT_ASC);

                return view('employee/employees')->with(array('employees' => $employees, 'url' => 'employees'));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying employees';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying employee page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display employees';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading employees';
            return view('error-msg')->with('msg', $error);
        }
    }


    public function selectUser(){

        //get the users from the api
        $users = getUsers();

        if(count($users) > 0){
            //return the view with a select box of users
            return view('employee/option')->with('users', $users);
        }else{
            $error = 'All users have previously been added as employees. 
            <br> <br> 
            Please either add a new user or add a new employee from scratch.';
            return view('error-msg')->with('msg', $error);
        }


    }

    /**
     * Show the form for creating a new resource.
     * just return view with no auto-populated values
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            if (session()->has('token')) {
                return view('employee/add-employee');
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying add employee page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying add employee form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying form';
            return view('error-msg')->with('msg', $e);

        }catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading add employee page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Show the form for creating a new employee using existing user.
     * return view with auto-populated values
     *
     * @return \Illuminate\Http\Response
     */
    public function createExisting(Request $request)
    {
        try {
            if (session()->has('token')) {

                //get userId from the input
                $userId = Input::get('user');

                //get user details from db via api using a function defined in functions.php
                $user = getUser($userId);

                return view('employee/add-employee-user')->with('user', $user);

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying add employee page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying add employee form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying form';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading add employee page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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

                $this->validate($request, [
                    'dateOfBirth' => 'required',
                    'sex' => 'required|max:255',
                    'mobile' => 'required|max:25',
                    'email' => 'required|max:255',
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255'
                ]);

                $first_name = ucwords(Input::get('first_name'));
                $last_name = ucwords(Input::get('last_name'));
                $dob = Input::get('dateOfBirth');
                $gender = Input::get('sex');
                $mobile = Input::get('mobile');
                $email = Input::get('email');

                $dob = Carbon::createFromFormat('m/d/Y', $dob)->format('Y-m-d');

                $response = $client->post(Config::get('constants.API_URL') . 'employees', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'dateOfBirth' => $dob, 'sex' => $gender,
                            'mobile' => $mobile, 'email' => $email, 'company_id' => $compId
                        )
                    )
                );

                $employee = json_decode((string)$response->getBody());

                //direct user based on whether record stored successfully or not
                if ($employee->success == true) {
                    //display confirmation page
                    $theAction = 'The new employee has been added to the system and an email has been sent to 
                    the supplied email address advising them to download the ODIN Case Management Mobile App and 
                    create a password for their
                    account. 
                     <br> <br>
                     Please advise the employee to check their junk email folder for the email 
                    in case it has not landed in their inbox';

                    return view('confirm')->with(array('theAction' => $theAction));

                } else {
                    $error = 'Error storing employee';
                    $errors = collect($error);
                    return view('/employee/add-employee')->with('errors', $errors);
                }
            } //not authenticated
            else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('employees/create')
                ->withInput()
                ->withErrors('Operation failed. Please ensure input valid and email unique.');

        } catch (\ErrorException $error) {
            return Redirect::to('employees/create')
                ->withInput()
                ->withErrors('Error storing employee');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('employees/create')
                ->withInput()
                ->withErrors('Error storing employee details. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeExisting(Request $request, $userId)
    {
        try {
            if (session()->has('token')) {

                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                //validate user input
                $this->validate($request, [
                    'dateOfBirth' => 'required',
                    'sex' => 'required|max:255',
                    'mobile' => 'required|max:25'
                ]);

                //user-input data
                $dob = Input::get('dateOfBirth');
                $gender = Input::get('sex');
                $mobile = Input::get('mobile');

                //format dob
                $dob = Carbon::createFromFormat('m/d/Y', $dob)->format('Y-m-d');

                //store in db
                $response = $client->post(Config::get('constants.API_URL') . 'employees/'.$userId, array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array(
                            'dateOfBirth' => $dob, 'sex' => $gender,
                            'mobile' => $mobile, 'userId' => $userId
                        )
                    )
                );

                $employee = json_decode((string)$response->getBody());

                //direct user based on whether record stored successfully or not
                if ($employee->success == true) {
                    //display confirmation page
                    $theAction = 'The console user has been added as an employee. 
                    <br><br>
                    An email has been sent to 
                    their email address with a link to download the ODIN Case Management Mobile App. The employee can
                    login to the mobile app using their current user password.
                     <br> <br> 
                    Please advise the employee to check their junk email folder for the email 
                    in case it has not landed in their inbox';

                    return view('confirm')->with(array('theAction' => $theAction));

                } else {
                    $error = 'Error storing employee';
                    $errors = collect($error);
                    return view('/employee/add-employee')->with('errors', $errors);
                }
            } //not authenticated
            else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('employees/create')
                ->withInput()
                ->withErrors('Operation failed. Please ensure input valid and email unique.');

        } catch (\ErrorException $error) {
            return Redirect::to('employees/create')
                ->withInput()
                ->withErrors('Error storing employee');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('employees/create')
                ->withInput()
                ->withErrors('Error storing employee details. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        }
    }

    /**
     * NOT CURRENTLY IN USE
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

//    public function show(Employee $employee)
//    {
//        try {
//            if (session()->has('token')) {
//                //shows the detail of a particular employee when accessed from /employees page
//                return view('employee.show', compact('employee'));
//            } else {
//                return Redirect::to('/login');
//            }
//        } catch (GuzzleHttp\Exception\BadResponseException $e) {
//            return view('/employees');
//        } catch (\ErrorException $error) {
//            return view('/employees');
//        }
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;


                $response = $client->get(Config::get('constants.API_URL') . 'employees/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $employees = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($employees == false) {

                    return verificationFailedMsg();
                }

                $employee = $employees[0];

                $t = $employee->dob;

                $dt = new DateTime($t);
                $dateBirth = $dt->format('m/d/Y');

                return view('employee/edit-employee')->with(array(
                    'employee' => $employee,
                    'dateBirth' => $dateBirth
                ));

            } //not authenticated
            else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying employee details';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying employee details for editing';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying employee details for update';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');


        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit employee page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                //validate input meet's db constraints
                $this->validate($request, [
                    'dateOfBirth' => 'required',
                    'sex' => 'required',
                    'mobile' => 'required|max:25',
                    'email' => 'required|max:255',
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255'
                ]);

                //get the data from the form
                $first_name = ucwords(Input::get('first_name'));
                $last_name = ucwords(Input::get('last_name'));
                $dob = Input::get('dateOfBirth');

                //process dob before adding to db
                $dateOfBirth = Carbon::createFromFormat('m/d/Y', $dob)->format('Y-m-d');

                $gender = Input::get('sex');
                $mobile = Input::get('mobile');
                $email = Input::get('email');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'employees/' . $id . '/update', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json',
                            'X-HTTP-Method-Override' => 'PUT'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'email' => $email, 'dateOfBirth' => $dateOfBirth, 'sex' => $gender, 'mobile' => $mobile
                        )
                    )
                );

                $employee = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($employee == false) {

                    return verificationFailedMsg();
                }

                //direct user based on whether record updated successfully or not
                if ($employee->success == true) {
                    $theAction = 'You have successfully edited the employee details';

                    return view('confirm')->with(array('theAction' => $theAction));
                } else {
                    return view('error-msg')->with('msg',
                        'Failed to update employee record successfully');
                }
            } else {
                //not authenticated
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('/employees/' . $id . '/edit')
                ->withInput()
                ->withErrors('Operation failed. Please check input and ensure email unique.');

        } catch (\ErrorException $error) {
            return Redirect::to('/employees/' . $id . '/edit')
                ->withInput()
                ->withErrors('Error updating employee details');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/employees/' . $id . '/edit')
                ->withInput()
                ->withErrors('Error updating employee. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'employees/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'
                    ]
                ]);

                $employee = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($employee == false) {

                    return verificationFailedMsg();
                }

                if ($employee->success == true) {
                    $theAction = 'You have successfully deleted the employee';

                    return view('confirm')->with(array('theAction' => $theAction));
                } else {
                    $theAction = 'Error deleting employee';

                    return view('confirm')->with(array('theAction' => $theAction));
                }

            } else {
                //not authenticated
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Operation Failed. Unable to delete employee.';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting employee';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error removing employee from database';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error removing employee from system';
            return view('error-msg')->with('msg', $error);
        }
    }
}

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
                //format dates to be mm/dd/yyyy for case notes
                foreach($employees as $i => $item){
                    //convert string date to DateTime and format date
                    $t = $employees[$i]->dob;

                    $dt = new DateTime($t);
                    $date = $dt->format('m/d/Y');

                    //add formattedDate to $employees object
                    $employees[$i]->dateBirth = $date;
                }

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

                $this->validate($request, [
                    'dateOfBirth' => 'required',
                    'sex' => 'required|max:255',
                    'mobile' => 'required',
                    'email' => 'required|max:255',
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255'
                ]);

                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                $dob = Input::get('dateOfBirth');
                $gender = Input::get('sex');
                $mobile = Input::get('mobile');
                $email = Input::get('email');
                $password=  Hash::make(str_random(8));
                $dob = Carbon::createFromFormat('m/d/Y', $dob)->format('Y-m-d');

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

                //display confirmation page
                $theAction = 'The new employee has been added to the system and an email has been sent to 
                the supplied email address advising them to download the OdinLite mobile app and create a password for their
                account. Please advise the employee to check their junk email folder for the email 
                in case it has not landed in their inbox';

                return view('confirm')->with(array('theAction' => $theAction));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $error = 'Please check input. Hint: Ensure email address is valid and is not already stored in the system';
            $errors = collect($error);
            return view('/employee/add-employee')->with('errors', $errors);
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

                $employee = $employees[0];

                $t = $employee->dob;

                $dt = new DateTime($t);
                $dateBirth = $dt->format('m/d/Y');

                return view('employee/edit-employee')->with(array(
                    'employee' => $employee,
                    'dateBirth' => $dateBirth
                ));

            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            $err = 'Error updating employee.';
            $errors = collect($err);
            return view('employee/edit-employee')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
            $e = 'Please fill in all required fields or check input';
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
    public function update(Request $request, $id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                //validate input meet's db constraints
                $this->validate($request, [
                    'dateOfBirth' => 'required',
                    'sex' => 'required|max:255',
                    'mobile' => 'required',
                    'email' => 'required|max:255',
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255'
                ]);

                //get the data from the form
                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                $dob = Input::get('dateOfBirth');

                //process dob before adding to db
                $dateOfBirth = dateFormat($dob);

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
                            'email' => $email, 'dateOfBirth'=> $dateOfBirth, 'sex'=>$gender, 'mobile'=> $mobile
                        )
                    )
                );

                $employee = json_decode((string)$response->getBody());

                //direct user based on whether record updated successfully or not
                if($employee->success == true)
                {
                    $theAction = 'You have successfully edited the employee details';

                    return view('confirm')->with(array('theAction' => $theAction));
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

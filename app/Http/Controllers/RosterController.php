<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Job_Location;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Location;
use Input;
use Carbon\Carbon;
//use View;

use App\Http\Controllers\EmployeeController;

class RosterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('home/rosters/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

        //        FIXME: bg displaying when page first loads. Shouldn't be.
        return view('home/rosters/create')->with(array('empList' => $empList, 'locList' =>$locList, 'checks' =>$checks));
    }

    public function employeeList(){
        $empList = Employee::all('id', 'first_name');
        return $empList;
    }

    public function locationList(){
        $locList = Location::all('id', 'name');
        return $locList;
    }

    public function checksCollection(){
        $checks = collect([0,1,2,3,4,5]);
        return $checks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

//    TODO: assigned_user_id to be changed to assigned_employee_id perhaps. Wait upon User/Employee setup in Web Console
    public function store(Request $request)
    {
//        TODO: save shift with a name that is auto
//        TODO: need the saved name added to job table as a column
//        TODO cont.: lower priority v1, after basic in place.
// Create and Save Shift btn = confirm msg saves saved with this name inc all particulars UnionSquare_JohnSmith_14thMay_9am-5pm
        //once 1 shift is created, the user needs the ability to use the saved shift to create a new shift. not edit
//        dropdown with shifts and a btn beside the dropdown to select the saved shift details
        //which then autopopulates all the fields for the user to edit.
        //        TODO v1 lower or v2: allow input of more than one location for the one create item (better usability)

        //variables for passing to view
        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

        $this->validate($request, [
            'assigned_user_id' => 'required',//TODO: improve. atm, if nothing is selected by the user, the default item is added to db. same for locations
            'locations' => 'required',
            'startDateTxt' => 'required',
            'startTime' => 'required',
            'endDateTxt' => 'required',
            'endTime' => 'required'
        ]);

        $job = new Job;

        //get data from form and insert laravel validated data into job table
        $job->assigned_user_id = Input::get('assigned_user_id');
        $job->company_id = 1;
        $location = Input::get('locations');
        $job->locations = $location;
        $job->checks = Input::get('checks');

        //get data from form for non laravel validated inputs
        $dateStart =Input::get('startDateTxt');//retrieved format = 05/01/2017
        $timeStart = Input::get('startTime');//hh:mm
        $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
        $timeEnd = Input::get('endTime');//hh:mm

        //process start date and time before adding to db
        $carbonStart = $this->jobDateTime($dateStart, $timeStart);
        $carbonEnd = $this->jobDateTime($dateEnd, $timeEnd);
        $lengthH = $this->jobDuration($carbonStart, $carbonEnd);

        //add data to table
        $job->job_scheduled_for = $carbonStart;
        $job->estimated_job_duration = $lengthH;

        $job->save();

        return view('confirm-create')->with(array('theData' => $location, 'entity' => 'Shift'));
    }

    public function jobDateTime($date, $time){
        $dtStr = $date . " " . $time;
        $carbonDT = Carbon::parse($dtStr);
        return $carbonDT;
    }

    public function jobDuration($carbonStart, $carbonEnd){
        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM / 60);//convert to hours
        return $lengthH;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::find($id);
        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

        return view('home/rosters/edit')->with(array('empList' => $empList, 'locList' =>$locList, 'checks' =>$checks, 'job'=> $job));
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
        //variables for passing to view
        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

        $this->validate($request, [
            'assigned_user_id' => 'required',//TODO: improve. atm, if nothing is selected by the user, the default item is added to db. same for locations
            'locations' => 'required',
            'startDateTxt' => 'required',
            'startTime' => 'required',
            'endDateTxt' => 'required',
            'endTime' => 'required'
        ]);
        $job = Job::find($id);

        //get data from form and insert laravel validated data into job table
        $job->company_id = 1;
        $job->assigned_user_id = Input::get('assigned_user_id');

        $location = Input::get('locations');
        $job->locations = $location;
        $job->checks = Input::get('checks');

        //get data from form for non laravel validated inputs
        $dateStart =Input::get('startDateTxt');//retrieved format = 05/01/2017
        $timeStart = Input::get('startTime');//hh:mm
        $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
        $timeEnd = Input::get('endTime');//hh:mm

        //process start date and time before adding to db
        $carbonStart = $this->jobDateTime($dateStart, $timeStart);
        $carbonEnd = $this->jobDateTime($dateEnd, $timeEnd);
        $lengthH = $this->jobDuration($carbonStart, $carbonEnd);

        //add data to table
        $job->job_scheduled_for = $carbonStart;
        $job->estimated_job_duration = $lengthH;

        $job->save();

//            $this->notifyViaForm(true);
        $theAction = 'edited the shift: ';
        return view('confirm')->with(array('theData' => $location,  'theAction' => $theAction));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

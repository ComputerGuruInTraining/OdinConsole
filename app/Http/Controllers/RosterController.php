<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Job_Location;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Location;
use Input;
use Carbon\Carbon;
use DateTime;
use DateInterval;
//use View;

use App\Http\Controllers\EmployeeController;

class RosterController extends Controller
{
//    TODO: HIGH select roster
// TODO: HIGH view entire roster

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formattedJobs = $this->jobList();
        return view('home/rosters/index')->with('formattedJobs', $formattedJobs);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //TODO: format date that displays on view
        $jobs = $this->jobList();
        $selectedJob = Job::find($id);
        return view('home/rosters/show')->with(array('jobs' => $jobs, 'selected' => $selectedJob));
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
        //TODO: HIGH auto-populate fields on edit page
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
        $job = Job::find($id);
        Job::destroy($id);
        return view('confirm')->with(array('theData'=> $job->locations, 'theAction' => 'deleted'));
    }

    public function jobList(){
        //define $formattedJobs collection to store formatted data after conversion of data from db
        $formattedJobs = collect([]);

        //retrieve ordered data from db
        $jobs = Job::orderBy('job_scheduled_for','asc')->orderBy('locations')->get();

        foreach($jobs as $job) {
            //process job_scheduled_for and duration and convert into start and end date and times
            $dbdt = $job->job_scheduled_for;
            $duration = $job->estimated_job_duration;

            //extract date and time from job_scheduled_for datetime
            $dtm = new DateTime($dbdt);
            $startDate = $this->stringDate($dtm);
            $startTime = $this->stringTime($dtm);

            //calculate end date and time using duration and job_scheduled_for
            $edt = $this->endDT($dbdt, $duration);//datetime format

            //extract date and time from end datetime object
            $endDate = $this->stringDate($edt);
            $endTime = $this->stringTime($edt);

            $employee = Employee::find($job->assigned_user_id);
            $employeeName = $employee->first_name." ".$employee->last_name;

            $formattedJobs->push(array(
                'id'=>$job->id,
                'locations'=>$job->locations,
                'checks'=>$job->checks,
                'employees' =>$employeeName,
                'startDate' => $startDate,
                'startTime' => $startTime,
                'endDate' => $endDate,
                'endTime' => $endTime
            ));
        }
        return $formattedJobs;
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
        $checks = collect([1,2,3,4,5]);
        return $checks;
    }

    public function endDT($startTime, $duration){
        $dt = new DateTime($startTime);//DateTime object
        $interval = 'PT'.$duration.'H';
        $edt = $dt->add(new DateInterval($interval));
        return $edt;
    }

    public function stringDate($dt){
        $date = $dt->format('m/d/Y');
        return $date;
    }

    public function stringTime($tm){
        $time = $tm->format("g"). '.' .$tm->format("i"). ' ' .$tm->format("a");

        if($time == '12.00 AM')
        {
            $time = 'Midnight';

        }
//        echo "<script>console.log( 'Debug Objects: " . $formattedTime . "' );</script>";
        return $time;
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
        //        echo "<script>console.log( 'Debug Objects: " . $formattedTime . "' );</script>";

        return $lengthH;
    }



    public static function confirmDelete($id){
        $job = Job::find($id);
        $desc = 'the shift at '.$job->locations.' on '.$job->job_scheduled_for;
        $id = $job->id;
        return view('confirm-delete')->with(array('fieldDesc' => $desc, 'id' => $id, 'url' => 'rosters'));
    }
}

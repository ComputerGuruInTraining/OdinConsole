<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Location;
use Input;
use Carbon\Carbon;
use DateTime;
use DateInterval;

// for console logging:
//        echo "<script>console.log( 'Debug Objects: " . $formattedTime . "' );</script>";
//                dd($job);
class RosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //retrieve job collection from db with formatted values for start time and end time and values compared for duplicates
        $jobs = $this->jobList();
        //group the collection by startDate for grouping as tbody in the view
        $groupJobs = $this->groupByDate($jobs);

        return view('home/rosters/index')->with(array('jobs' => $jobs, 'groupJobs' => $groupJobs));
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
        return view('home/rosters/create')->with(array('empList' => $empList, 'locList' => $locList));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
           //variables for passing to view
           $empList = $this->employeeList();
           $locList = $this->locationList();

        //TODO: improve. atm, if nothing is selected by the user, the default item is added to db. same for locations
        $this->validate($request, [
        'employees' => 'required',
        'locations' => 'required',
        'startDateTxt' => 'required',
        'startTime' => 'required',
        'endDateTxt' => 'required',
        'endTime' => 'required'
        ]);

           //get the data from the form and perform necessary calculations prior to inserting into db
           $dateStart = $this->formData($request);
           $theData = "the $dateStart";
           return view('confirm-create')->with(array('theData' => $dateStart, 'entity' => 'Shift', 'url' => 'rosters'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//NOTE: not using show at this point as index contains enough info
//    public function show($id)
//    {
//
//        $jobs = $this->jobList();
////        foreach($jobs as $job) {
////            $selectedJob = $jobs
////        }
//        $selectedJob = $jobs->find($id);
//                echo "<script>console.log( 'Debug Objects: " . $selectedJob . "' );</script>";
//
//        return view('home/rosters/show')->with(array('jobs' => $jobs, 'selected' => $selectedJob));
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::find($id);
        $locationName = $job->locations;

//        if employee exists
        if(Employee::find($job->assigned_user_id) != null) {
            $employee = Employee::find($job->assigned_user_id);
        }
        else{
            $employee = null;
        }

        $empList = $this->employeeList();
        $locList = $this->locationList();

        return view('home/rosters/edit')->with(array('empList' => $empList, 'locList' => $locList, 'job' => $job, 'employee' => $employee, 'locationName' => $locationName));
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
        $this->validate($request, [
            //TODO: v1 after MPV or v2. atm, if nothing is selected by the user, the default item is added to db. Should be no change if nothing selected for that field. same for locations.
//            'assigned_user_id' => 'required',
//            'locations' => 'required',
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
        $dateStart = Input::get('startDateTxt');//retrieved format = 05/01/2017
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
        $theAction = 'You have successfully edited the shift';
        return view('confirm')->with(array('theAction' => $theAction));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $job = Job::find($id);
        Job::destroy($id);
        $theAction = 'You have successfully deleted the shift';
        return view('confirm')->with('theAction', $theAction);
    }

    public function jobList()
    {
        //retrieve ordered data from db
        $jobs = Job::orderBy('job_scheduled_for', 'asc')->orderBy('locations')->get();

//        the index is needed for reassigning a value to the collection item
        foreach ($jobs as $i => $job) {

                //process job_scheduled_for and duration and convert into start and end date and times
                $dbdt = $job->job_scheduled_for;//string returned from db
                $duration = $job->estimated_job_duration;

                //extract date and time from job_scheduled_for datetime
                $dtm = new DateTime($dbdt);
                $jobs[$i]->startDate = $this->stringDate($dtm);
                //also add the startDate values to uniqueDate which will be used later to update startDate values in uniqueDate, but preserve the values in startDate field
                $jobs[$i]->uniqueDate = $jobs[$i]->startDate;
                $jobs[$i]->startTime = $this->stringTime($dtm);

                //calculate end date and time using duration and job_scheduled_for
                $edt = $this->endDT($dbdt, $duration);//datetime format

                //extract date and time from end datetime object
                $jobs[$i]->endDate = $this->stringDate($edt);

                $jobs[$i]->endTime = $this->stringTime($edt);

                $jobs[$i]->uniqueLocations = $job->locations;

                //if employee exists
            if(Employee::find($job->assigned_user_id) != null) {
                $employee = Employee::find($job->assigned_user_id);
                $jobs[$i]->employeeName = $employee->first_name . " " . $employee->last_name;
            }
            else{
                $jobs[$i]->employeeName = 'not found';

            }
        }
//        pass data to compareValues function in order to only display unique data for each date, rather than duplicating the date and the time when they are duplicate values
        $jobs = $this->compareValues($jobs, 'startDate', 'uniqueDate', 'uniqueLocations', 'checks', 'startTime', 'endTime');

        //if the time is 12:00am, convert to midnight for usability
        foreach ($jobs as $i => $job) {
            $jobs[$i]->startTime = $this->timeMidnight($jobs[$i]->startTime);
            $jobs[$i]->endTime = $this->timeMidnight($jobs[$i]->endTime);
        }
        return $jobs;
    }

    public function groupByDate($jobs)
    {
        //group the collection by startDate for grouping as tbody in the view
        $groupedJobs = $jobs->groupBy(function ($job, $key) {
            return $job['startDate'];
        });
        return $groupedJobs;
    }

//function defined for global use
    public function compareValues($jobs, $date, $uniqueDate, $uniqueLocations = null, $checks = null, $startTime = null, $endTime = null)
    {
            for ($i = 0; $i < $jobs->count(); $i++) {
                for ($j = 0; $j < $jobs->count(); $j++) {
                    //if startDate the same, preserve the startDate values for future comparisons and use:
                    //and add null to the uniqueDate field which was assigned the values in the startDate field previously,
                    if ($jobs[$i][$date] == $jobs[$j][$date]) {
                        if ($i > $j) {
                            $jobs[$i][$uniqueDate] = null;
                        }
                        //if locations and checks and startTime and endTime the same,
                        //change values of these fields to null for the duplicates:
                        if (($jobs[$i][$uniqueLocations] == $jobs[$j][$uniqueLocations])
                            && ($jobs[$i][$checks] == $jobs[$j][$checks])
                            && ($jobs[$i][$startTime] == $jobs[$j][$startTime])
                            && ($jobs[$i][$endTime] == $jobs[$j][$endTime])
                        ) {
                            if ($i > $j) {
                                $jobs[$i][$startTime] = null;
                                $jobs[$i][$endTime] = null;
                                $jobs[$i][$uniqueLocations] = null;
                                $jobs[$i][$checks] = null;
                            }
                            //if only locations and checks the same, then:
                        } else if (($jobs[$i][$uniqueLocations] == $jobs[$j][$uniqueLocations])
                            && ($jobs[$i][$checks] == $jobs[$j][$checks])
                        ) {
                            if ($i > $j) {
                                $jobs[$i][$uniqueLocations] = null;
                                $jobs[$i][$checks] = null;
                            }
                        }
                    }
                }
            }
        return $jobs;
    }

    public function formData($request){
        //data for validation
        $locationArray = Input::get('locations');
        $employeeArray = Input::get('employees');
        $checks = Input::get('checks');
        $companyId = 1;

        //get data from form for non laravel validated inputs
        $dateStart = Input::get('startDateTxt');//retrieved format = 05/01/2017
        $timeStart = Input::get('startTime');//hh:mm
        $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
        $timeEnd = Input::get('endTime');//hh:mm

        //process start date and time before adding to db
        $carbonStart = $this->jobDateTime($dateStart, $timeStart);
        $carbonEnd = $this->jobDateTime($dateEnd, $timeEnd);
//        TODO: duration needs to be a double (db expecting an integer)
        $lengthH = $this->jobDuration($carbonStart, $carbonEnd);

        //for each employee...
        for($emp=0; $emp<sizeof($employeeArray); $emp++) {
//            insert a job record for each location
            for ($loc = 0; $loc < sizeof($locationArray); $loc++) {
                $job = new Job;
                //insert laravel validated data into job table
                $job->assigned_user_id = $employeeArray[$emp];
                $job->company_id = $companyId;
                $job->locations = $locationArray[$loc];
                $job->checks = $checks;

                //insert non-laravel validated data to table
                $job->job_scheduled_for = $carbonStart;
                $job->estimated_job_duration = $lengthH;

                $job->save();
            }
        }
        return $dateStart;
    }

    public function employeeList()
    {
        $empList = Employee::all('id', 'first_name', 'last_name');
        return $empList;
    }

    public function locationList()
    {
        $locList = Location::all('id', 'name');
        return $locList;
    }

    public function endDT($startTime, $duration)
    {
        $dt = new DateTime($startTime);//DateTime object
        $interval = 'PT' . $duration . 'H';
        $edt = $dt->add(new DateInterval($interval));
        return $edt;
    }

    public function stringDate($dt)
    {
        $date = $dt->format('m/d/Y');
        return $date;
    }

    public function stringTime($tm)
    {
        $time = $tm->format("g"). '.' .$tm->format("i"). ' ' .$tm->format("a");
        return $time;
    }

    public function timeMidnight($time){
        if($time != null) {
            if($time == '12.00 am')
            {
                $time = 'Midnight';
            }
        }
        return $time;
    }

    public function jobDateTime($date, $time)
    {
        $dtStr = $date . " " . $time;
        $carbonDT = Carbon::parse($dtStr);
        return $carbonDT;
    }

    public function jobDuration($carbonStart, $carbonEnd)
    {
        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM / 60);//convert to hours
        return $lengthH;
    }

    public static function confirmDelete($id)
    {
        $job = Job::find($id);
        $desc = 'the shift at ' . $job->locations . ' on ' . $job->job_scheduled_for;
        $id = $job->id;
        return view('confirm-delete')->with(array('fieldDesc' => $desc, 'id' => $id, 'url' => 'rosters'));
    }
}

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

//use View;

//use App\Http\Controllers\EmployeeController;

// for console logging:
//        echo "<script>console.log( 'Debug Objects: " . $formattedTime . "' );</script>";
class RosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    protected $formattedJobs;
////    protected $grouped;
//    protected $count;
//        TODO: NOW: remove tr borders, instead tbody borders
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
    //    TODO v1 after other functionality (lower): Do not auto-complete add shift with Employee and Location and Checks: instead have a select option: please select employee etc
    //    TODO: low v2 or v1 after other functionality: sort select lists on add shift page in alpha order
    public function create()
    {
        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

        //        FIXME: bg displaying when page first loads. Shouldn't be.
        return view('home/rosters/create')->with(array('empList' => $empList, 'locList' => $locList, 'checks' => $checks));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
//    TODO High v1 possibly v2 but not very usable: If required fields are left blank, the validation message appears as expected, however
//    TODO continued: the fields that haven't been completed are not saved so the user needs to complete all fields again and more risk that wrong item is created
// TODO continued considering employee and location have default values.

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

        return view('confirm-create')->with(array('theData' => $location, 'entity' => 'Shift'));
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
//        $jobs = $this->jobList();
//        $job = $jobs->find($id);//need the job from the jobList because the jobOject in jobList contains start time and end time etc. otherwise need to recompute
        $locationName = $job->locations;
        $employee = Employee::find($job->assigned_user_id);

        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

//                echo "<script>console.log( 'Debug Objects: " . $ . "' );</script>";
        return view('home/rosters/edit')->with(array('empList' => $empList, 'locList' => $locList, 'checks' => $checks, 'job' => $job, 'employee' => $employee, 'locationName' => $locationName));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//    TODO v1 after other functionality: ensure if start date is input as the same as i
    public function update(Request $request, $id)
    {
        //TODO: HIGH auto-populate fields on edit page
//        //variables for passing to view
//        $empList = $this->employeeList();
//        $locList = $this->locationList();
//        $checks = $this->checksCollection();

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
        $theAction = 'edited the shift';
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
        $theAction = 'deleted the shift';
        return view('confirm')->with('theAction', $theAction);
    }

    public function jobList()
    {
        //retrieve ordered data from db
        $jobs = Job::orderBy('job_scheduled_for', 'asc')->orderBy('locations')->get();
        $modifiedJobs = $jobs;

//        the index is needed for reassigning a value to the collection item
        foreach ($modifiedJobs as $i => $job) {
            //process job_scheduled_for and duration and convert into start and end date and times
            $dbdt = $job->job_scheduled_for;//string returned from db
            $duration = $job->estimated_job_duration;

            //extract date and time from job_scheduled_for datetime
            $dtm = new DateTime($dbdt);
            $modifiedJobs[$i]->startDate = $this->stringDate($dtm);
            //also add the startDate values to uniqueDate which will be used later to update startDate values in uniqueDate, but preserve the values in startDate field
            $modifiedJobs[$i]->uniqueDate = $this->stringDate($dtm);

            $modifiedJobs[$i]->startTime = $this->stringTime($dtm);

            //calculate end date and time using duration and job_scheduled_for
            $edt = $this->endDT($dbdt, $duration);//datetime format

            //extract date and time from end datetime object
            $modifiedJobs[$i]->endDate = $this->stringDate($edt);

            $modifiedJobs[$i]->endTime = $this->stringTime($edt);

            $modifiedJobs[$i]->uniqueLocations =  $job->locations;

            $employee = Employee::find($job->assigned_user_id);
            $modifiedJobs[$i]->employeeName = $employee->first_name . " " . $employee->last_name;
        }

//        pass data to compareValues function in order to only display unique data for each date, rather than duplicating the date and the time when they are duplicate values
        $modifiedJobs = $this->compareValues($modifiedJobs, 'startDate', 'uniqueDate', 'uniqueLocations', 'checks', 'startTime', 'endTime');

        //if the time is 12:00am, convert to midnight for usability
        foreach ($modifiedJobs as $i => $job) {
            $modifiedJobs[$i]->startTime = $this->timeMidnight($modifiedJobs[$i]->startTime);
            $modifiedJobs[$i]->endTime = $this->timeMidnight($modifiedJobs[$i]->endTime);
        }
        return $modifiedJobs;
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
    public function compareValues($modifiedJobs, $date, $uniqueDate, $uniqueLocations = null, $checks = null, $startTime = null, $endTime = null)
    {
            for ($i = 0; $i < $modifiedJobs->count(); $i++) {
                for ($j = 0; $j < $modifiedJobs->count(); $j++) {
                    //if startDate the same, preserve the startDate values for future comparisons and use:
                    //and add null to the uniqueDate field which was assigned the values in the startDate field previously,
                    if ($modifiedJobs[$i][$date] == $modifiedJobs[$j][$date]) {
                        if ($i > $j) {
                            $modifiedJobs[$i][$uniqueDate] = null;
                        }
                        //if locations and checks and startTime and endTime the same,
                        //change values of these fields to null for the duplicates:
                        if (($modifiedJobs[$i][$uniqueLocations] == $modifiedJobs[$j][$uniqueLocations])
                            && ($modifiedJobs[$i][$checks] == $modifiedJobs[$j][$checks])
                            && ($modifiedJobs[$i][$startTime] == $modifiedJobs[$j][$startTime])
                            && ($modifiedJobs[$i][$endTime] == $modifiedJobs[$j][$endTime])
                        ) {
                            if ($i > $j) {
                                $modifiedJobs[$i][$startTime] = null;
                                $modifiedJobs[$i][$endTime] = null;
                                $modifiedJobs[$i][$uniqueLocations] = null;
                                $modifiedJobs[$i][$checks] = null;
                            }
                            //if only locations and checks the same, then:
                        } else if (($modifiedJobs[$i][$uniqueLocations] == $modifiedJobs[$j][$uniqueLocations])
                            && ($modifiedJobs[$i][$checks] == $modifiedJobs[$j][$checks])
                        ) {
                            if ($i > $j) {
                                $modifiedJobs[$i][$uniqueLocations] = null;
                                $modifiedJobs[$i][$checks] = null;
                            }
                        }
                    }
                }
            }
        return $modifiedJobs;
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

    public function checksCollection()
    {
        $checks = collect([1, 2, 3, 4, 5]);
        return $checks;
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

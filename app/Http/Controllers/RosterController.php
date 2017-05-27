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

class RosterController extends Controller
{
//    TODO: HIGH select roster
// TODO: HIGH view entire roster

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $formattedJobs;
//    protected $grouped;
//    protected $count;

    public function index()
    {
       $jobs = $this->jobList();
       $groupJobs = $this->groupByDate($jobs);
       $counts = $this->countGroups($groupJobs);
//        foreach ($counts as $counter) {
//            echo "<script>console.log( 'counter = " . $counter . "' );</script>"; //7 but no results coming up below from 3-7./30
//        }

        return view('home/rosters/index')->with(array('jobs' => $jobs, 'groupJobs' => $groupJobs, 'counts' => $counts));
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
//    TODO: either store and retrieve start time and start date in db for ease of use througout app, or find the item with the $id in the $jobList and use the $jobList values
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
        $locationName = $job->locations;
        $employee = Employee::find($job->assigned_user_id);

        $empList = $this->employeeList();
        $locList = $this->locationList();
        $checks = $this->checksCollection();

        return view('home/rosters/edit')->with(array('empList' => $empList, 'locList' =>$locList, 'checks' =>$checks, 'job'=> $job, 'employee'=>$employee, 'locationName' =>$locationName));
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
        $theAction = 'edited the shift';
        return view('confirm')->with(array('theAction' => $theAction));
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
        return view('confirm')->with(array('theEntity'=> '', 'theData'=> $job->locations, 'theAction' => 'deleted'));
    }

    public function jobList()
    {
        //retrieve ordered data from db
        $jobs = Job::orderBy('job_scheduled_for', 'asc')->orderBy('locations')->get();
        $modifiedJobs = $jobs;

        foreach ($modifiedJobs as $i => $job) {
            //process job_scheduled_for and duration and convert into start and end date and times
            $dbdt = $job->job_scheduled_for;//string returned from db
            $duration = $job->estimated_job_duration;

            //extract date and time from job_scheduled_for datetime
            $dtm = new DateTime($dbdt);
            $modifiedJobs[$i]->startDate = $this->stringDate($dtm);
            $modifiedJobs[$i]->uniqueDate = $this->stringDate($dtm);

            $modifiedJobs[$i]->startTime = $this->stringTime($dtm);


            //calculate end date and time using duration and job_scheduled_for
            $edt = $this->endDT($dbdt, $duration);//datetime format

            //extract date and time from end datetime object
            $modifiedJobs[$i]->endDate = $this->stringDate($edt);
            $modifiedJobs[$i]->endTime = $this->stringTime($edt);

            $employee = Employee::find($job->assigned_user_id);
            $modifiedJobs[$i]->employeeName = $employee->first_name . " " . $employee->last_name;

        }

//        display blank data for same date and also for same date and same start and end time
        $modifiedJobs = $this->compareValues($modifiedJobs,  'startDate', 'uniqueDate', 'locations', 'checks', 'startTime', 'endTime');
//        display blank data for same location and checks when same date
        $modifiedJobs = $this->compareValues($modifiedJobs,  'startDate', 'uniqueDate', 'locations', 'checks');

        $groupedJobs = $modifiedJobs->groupBy(function($modifiedJob, $key){return $modifiedJob['startDate'];});
//        echo "<script>console.log( 'Grouped Array: Index = ".$groupedJobs. "' );</script>";
//        $allJobs = $groupedJobs->all();//returns the underlying array from a collection
//        echo "<script>console.log( 'Grouped Array: Index = ".$allJobs['05/02/2017']. "' );</script>";

        $groupedJobs->toArray();

//
        return $modifiedJobs;
    }

    public function groupByDate($jobs)
    {
        $groupedJobs = $jobs->groupBy(function ($job, $key) {
            $job->key = $key;
            if((($job->key)%2) == 0) {
//                echo "<script>console.log( 'Key for grouped = " . $job->key . "' );</script>";
            }


            return $job['startDate'];
        });
//        $groupedArray = $groupedJobs->toArray();
//        if(is_array($groupedArray)) {
//            echo "<script>console.log( 'Array Keys = array' );</script>";
//
//            echo "<script>console.log( 'Array Keys = " . $groupedArray['05/02/2017']->locations . "' );</script>";
//        }
//        else{
//            echo "<script>console.log( 'Array Keys isnt array' );</script>";
//
//
//        }
//        echo "<script>console.log( 'Grouped Array: Index = " . $groupedJobs . "' );</script>";
//

//////            $uniqueDate = $job->uniqueDate;//many different values
//////            if($uniqueDate != null) {
////                foreach ($groupedJobs->get($job->uniqueDate) as $shift) {
////                    echo "<script>console.log( 'Shift Employee Name = " . $shift->employeeName . "' );</script>";
////                    echo "<script>console.log( 'Shift Employee Key = " . $shift . "' );</script>";
////
////                    echo "<script>console.log( 'Shift Employee Key = " . $shift . "' );</script>";
////                    $this->count = collect();
////                    foreach($this->count as $counts) {
////                        if ($shift->uniqueDate == $shift->startDate) {
////                            $counts++;
////                            echo "<script>console.log( Count = " . $counts . "' );</script>";
////
////
////                        }
////                    }
////
////                }
//////
//        }
//////        $allJobs = $groupedJobs->all();//returns the underlying array from a collection
////        echo "<script>console.log( 'Grouped Array: Index = ".$allJobs['05/02/2017']. "' );</script>";
//        $count = 0;
        //$object => string)||($string => $value(dt??)

//            foreach()
////            $locations = 'locations';
////            $assigned_user_id = 'assigned_user_id';
//

//        $count3 = 0;
//        $countValue = 0;
//        $count = collect();
//        foreach($jobs as $job) {
//            if($job->uniqueDate )
//            foreach ($groupedJobs->get($job->startDate) as $shift) {
//
//$count2++;
//                echo "<script>console.log( 'count2 = " . $count2 . "' );</script>";//79 if unique Date 7
//
//                    foreach($groupedJobs as $index => $formattedJob) {
//                    if($index == $shift->startDate) {
//                        $count++;
//                        echo "<script>console.log( 'Shift Start Date = " . $shift->startDate . "' );</script>";
//                        echo "<script>console.log( 'Count inner loop = " . $count . "' );</script>";//79 if unique Date 7
////                    }
//                }
//
//            }
//        }
//        foreach($jobs as $job) {
//        $count = 0;
//        $count2 = 0;
//        $counts = collect([]);
//        foreach ($groupedJobs as $index => $formattedJob) {
////
//////                echo "<script>console.log( 'Index = " . $index . "' );</script>";
////                count[0] = $countValue;
////                $countValue++;
////                $countValue = 0;
////                $count++;
//            echo "<script>console.log( 'Index = " . $index . "' );</script>";
////            echo "<script>console.log( 'count1 = " . $count . "' );</script>";//when count 1, 23. when count 2, 46 then 3-7 no results.
//
//
////            foreach($counts as $count) {
//              $count2 = 0;
//                foreach ($groupedJobs->get($index) as $shift) {
//                    if ($index == $shift->startDate) {
////
////
//                        $count2++;
////                    $count++;
////                echo "<script>console.log( 'Data in 2nd loop = " . $shift->startDate . "' );</script>";
//////
//
//////
////////                    $count3++;
////////                echo "<script>console.log( 'Data in 3rd loop = " . $shift->startDate . "' );</script>";
//////
//////                echo "<script>console.log( 'count3 = " . $count3 . "' );</script>";//when count 1, 23. when count 2, 46 then 3-7 no results.//30
//////                    if($index == $shift->uniqueDate) {
////////                        foreach($jobs as $job){
////////                            $count++;
//
////////                        }
////                    }
//                    }
//
//
////                }
//            }
//            echo "<script>console.log( 'count2 = " . $count2 . "' );</script>"; //7 but no results coming up below from 3-7./30
//            $counts->push($count2);
//        }
//        echo "<script>console.log( 'count2 = " . $counts . "' );</script>"; //7 but no results coming up below from 3-7./30
////


return $groupedJobs;
}

public function countGroups($groupedJobs){
    $counts = collect([]);

    foreach ($groupedJobs as $index => $formattedJob) {
//
////                echo "<script>console.log( 'Index = " . $index . "' );</script>";
//                count[0] = $countValue;
//                $countValue++;
//                $countValue = 0;
//                $count++;
        echo "<script>console.log( 'Index = " . $index . "' );</script>";
//            echo "<script>console.log( 'count1 = " . $count . "' );</script>";//when count 1, 23. when count 2, 46 then 3-7 no results.


//            foreach($counts as $count) {
        $count2 = 0;
        foreach ($groupedJobs->get($index) as $shift) {

            if ($index == $shift->startDate) {
//
//
                $count2++;
//                    $count++;
//                echo "<script>console.log( 'Data in 2nd loop = " . $shift->startDate . "' );</script>";
////

////
//////                    $count3++;
//////                echo "<script>console.log( 'Data in 3rd loop = " . $shift->startDate . "' );</script>";
////
////                echo "<script>console.log( 'count3 = " . $count3 . "' );</script>";//when count 1, 23. when count 2, 46 then 3-7 no results.//30
////                    if($index == $shift->uniqueDate) {
//////                        foreach($jobs as $job){
//////                            $count++;

//////                        }
//                    }
            }
//                echo "<script>console.log( 'count2 = " . $count . "' );</script>"; //7 but no results coming up below from 3-7./30


//                }
//            }
        }
        echo "<script>console.log( 'count2 = " . $count2 . "' );</script>"; //7 but no results coming up below from 3-7./30


        $counts->push($count2);

    }
//    echo "<script>console.log( 'count2 = " . $counts[2] . "' );</script>"; //7 but no results coming up below from 3-7./30



    return $counts;


}

    public function compareValues($modifiedJobs, $value, $modifiedValue, $optionalValue1 = null, $optionalValue2 = null, $optionalValue3 = null, $optionalValue4 = null)
    {
        if (($optionalValue1) && ($optionalValue2)&&($optionalValue3)&&($optionalValue4)) {
        for ($i = 0; $i < $modifiedJobs->count(); $i++) {
            for ($j = 0; $j < $modifiedJobs->count(); $j++) {
                //if for eg startDate the same, then:
                if ($modifiedJobs[$i][$value] == $modifiedJobs[$j][$value]) {
                            if ($i > $j) {
                            $modifiedJobs[$i][$modifiedValue] = null;
                            }
                        if (($modifiedJobs[$i][$optionalValue1] == $modifiedJobs[$j][$optionalValue1])
                            && ($modifiedJobs[$i][$optionalValue2] == $modifiedJobs[$j][$optionalValue2])
                            &&($modifiedJobs[$i][$optionalValue3] == $modifiedJobs[$j][$optionalValue3])
                            &&($modifiedJobs[$i][$optionalValue4] == $modifiedJobs[$j][$optionalValue4]))  {
                            if ($i > $j) {
                                $modifiedJobs[$i][$optionalValue3] = null;
                                $modifiedJobs[$i][$optionalValue4] = null;
                                $modifiedJobs[$i][$optionalValue1] = null;
                                $modifiedJobs[$i][$optionalValue2] = null;
                            }
                        }
                        else  if (($modifiedJobs[$i][$optionalValue1] == $modifiedJobs[$j][$optionalValue1])
                            && ($modifiedJobs[$i][$optionalValue2] == $modifiedJobs[$j][$optionalValue2]))
                            {
                                if ($i > $j) {
                                    $modifiedJobs[$i][$optionalValue1] = null;
                                    $modifiedJobs[$i][$optionalValue2] = null;
                                }
                            }
                        }
                    }
                }
            }
        return $modifiedJobs;
    }

    public function employeeList(){
        $empList = Employee::all('id', 'first_name', 'last_name');
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
//        $time = $tm->format("g"). '.' .$tm->format("i"). ' ' .$tm->format("a");
        $time = $tm->format("G"). '.' .$tm->format("i");

//        if($time == '12.00 AM')
//        {
//            $time = 'Midnight';
//
//        }
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

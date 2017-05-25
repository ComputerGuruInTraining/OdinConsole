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
    protected $formattedJobs;

    public function index()
    {
        $formattedJobs = $this->jobList();
//        $unique = $this->removeDups();
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
//        $locationString = (string)$locationName;
        $employee = Employee::find($job->assigned_user_id);
//        $locationRecord = Location::where('name', '=', $locationString);
//        echo "<script>console.log( 'Location: " . $locationRecord->name . "' );</script>";

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
//
//    public function removeDups(){
//
//        $jobsCompare = array();
////        $jobsCompare2 = collect();
//
//        for ($c = 0; $c < $this->formattedJobs->count(); $c++) {
//            $jobsCompare[$c] = $this->formattedJobs[$c]['startDate'];
////            $jobsCompare2[$c] = $this->formattedJobs[$c]['startDate'];
//            echo "<script>console.log( 'Array Compare: " . $jobsCompare[$c] . "' );</script>";
//        }
//
////        $intersect = $jobsCompare->intersect($jobsCompare2);
//
//        $unique = array_unique($jobsCompare);
//
//
//        foreach($unique as $item){
//
//            echo "<script>console.log( 'Array Unique: " . $item . "' );</script>";
//        }
//
//        return $unique;

//    }

    public function jobList()
    {
        //define $formattedJobs collection to store formatted data after conversion of data from db
//        $this->formattedJobs = collect([]);
//        $arrayCompare = collect([]);
//        echo "<script>console.log( 'Array Declared line 215: " . $arrayCompare . "' );</script>";

//        $comparedJobs = collect([]);

        //retrieve ordered data from db
        $jobs = Job::orderBy('job_scheduled_for', 'asc')->orderBy('locations')->get();
//        $jobsGrouped = $jobs->groupBy('job_scheduled_for');
        $modifiedJobs = $jobs;

        foreach ($modifiedJobs as $job) {
            //process job_scheduled_for and duration and convert into start and end date and times
            $dbdt = $job->job_scheduled_for;//string returned from db
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
            $employeeName = $employee->first_name . " " . $employee->last_name;
//            $job->checks = 5;
            $job->job_scheduled_for = $startDate;
            echo "<script>console.log( 'Array Grouped: " . $job->job_scheduled_for . "' );</script>";



//            $this->formattedJobs->push(array(
//                'id' => $job->id,
//                'locations' => $job->locations,
//                'checks' => $job->checks,
//                'employees' => $employeeName,
//                'startDate' => $startDate,
//                'startTime' => $startTime,
//                'endDate' => $endDate,
//                'endTime' => $endTime,
//            ));
        }

//        $comparedJobs = $this->formattedJobs;
//        $groupedJobs = collect([]);
        for ($i = 0; $i < $modifiedJobs->count(); $i++) {
            for ($j = 0; $j < $modifiedJobs->count(); $j++) {
                if ($modifiedJobs[$i]['job_scheduled_for'] == $modifiedJobs[$j]['job_scheduled_for']) {
                    if (($i == $j) || ($i < $j)) {
                        $modifiedJobs[$i]['job_scheduled_for'] = $modifiedJobs[$i]['job_scheduled_for'];//keep current value

                    } else if ($i > $j) {
                        $modifiedJobs[$i]['job_scheduled_for'] = null;

                    }
                }


            }
        }
//
//
//
//
//
//            }
//
//
//
//
//        }

        return $modifiedJobs;
    }

        //collection transform through the collection and
        //if the date matches the date of other items in the collection
        //and the location matches the location
        //return the items that match this criteria (intersect)


        //loop through arrayCompare
//        for ($c = 0; $c < $this->formattedJobs->count(); $c++) {
            //loop through formattedJobs
//            for ($j = 0; $j < $this->formattedJobs->count(); $j++) {
//                //loop through formattedJobs again to compare the values in the array
//                $dateToArray = false;
//                for ($i = 1; $i < $this->formattedJobs->count(); $i++) {
//
//                    if ($this->formattedJobs[$i]['startDate'] == $this->formattedJobs[$j]['startDate']){
//                        if($dateToArray == false){
//                            $arrayCompare[$j] = $this->formattedJobs[$j]['startDate'];
//                            $arrayCompare[$i] = "";
////                        $arrayCompare->push(array(
////                        'startDate' => $this->formattedJobs[$j]['startDate'],
////
//////                        'startDate' => "a",
////                        'locations' => $this->formattedJobs[$i]['locations'],
//
////                        'locations' => "a"
//
////));
////                        $arrayCompare[$i]
//
////                    echo "<script>console.log( 'Assigned Value: " . $this->formattedJobs[$j]['locations'] . "' );</script>";
//                        echo "<script>console.log( 'Array Values: " . $arrayCompare[$j] . "' );</script>";
//                        echo "<script>console.log( 'Array Values: " . $arrayCompare[$i] . "' );</script>";
//
//
////                        echo "<script>console.log( 'ssigned Value: " . $arrayCompare[$i]['startDate'] . "' );</script>";
//
//
//
//                    $dateToArray = true;
//
//                }
//                else if(($this->formattedJobs[$i]['startDate'] == $this->formattedJobs[$j]['startDate'])&&($dateToArray == true)){
//                    $arrayCompare[$j] = "";
//
//                }
//                }
//            }
//        }
//        echo "<script>console.log( 'Array: " . $arrayCompare. "' );</script>";
//
//
////        for ($p = 0; $p < $this->formattedJobs->count(); $p++) {
////            $this->formattedJobs[$p]->put('datesFiltered', $arrayCompare[$p]);
////
////
////        }
////        for ($x = 0; $x <= $this->formattedJobs->count(); $x++) {
//        for ($x = 0; $x < 20; $x++) {
//
////            if ($this->formattedJobs[$x]){
//            echo "<script>console.log( 'Array in Loop: " . $arrayCompare[1] . "' );</script>";
//
////            if($arrayCompare[$x]) {
//                $comparedJobs->push(array(
//
//                    'id' => $this->formattedJobs[$x]['id'],
//                    'locations' => $this->formattedJobs[$x]['locations'],
//                    'checks' => $this->formattedJobs[$x]['checks'],
//                    'employees' => $this->formattedJobs[$x]['employees'],
//
//                        'startDate' => $arrayCompare[$x+1],
//
//                    'startTime' => $this->formattedJobs[$x]['startTime'],
//                    'endDate' => $this->formattedJobs[$x]['endDate'],
//                    'endTime' => $this->formattedJobs[$x]['endTime']
//
//                ));
//            }
//
////        }
//                    $comparedJobs->push(array(
//                'id' => $this->formattedJobs['id'],
////            'locations' => $this->formattedJobs->locations,
//                'checks' => $this->formattedJobs[$x]['checks'],
//                'employees' => $this->formattedJobs[$x]['employees'],
//                'startDate' => $arrayCompare[$x],
//                'startTime' => $this->formattedJobs[$x]['startTime'],
//                'endDate' => $this->formattedJobs[$x]['endDate'],
//                'endTime' => $this->formattedJobs[$x]['endTime']
//            ));

//        return $comparedJobs;
        //        foreach($this->formattedJobs as $formattedJob)
//        {
//            for($i=0; $i < $formattedJobs->count(); $i++ )
//            {
//                if($formattedJobs[$i]['startDate'] == $formattedJob['startDate']) {
//                    if($formattedJobs[$i]['locations'] == $formattedJob['locations'])
//                    {
//                        echo "<script>console.log( 'Assigned Value: " . $formattedJobs[$i]['locations'] . "' );</script>";
//                        echo "<script>console.log( 'Assigned Value: " . $formattedJob['locations'] . "' );</script>";
//
//
//                        $formattedJob['locations'] = "As Above";
//
//                        echo "<script>console.log( 'Assigned Value: " . $formattedJob['locations'] . "' );</script>";
//
//                    }
//                }
//            }
//        }
        //loop through the entire array
//        for($i=0; $i < $formattedJobs->count(); $i++ ) {

//            $groupedLocation = $this->formattedJobs->map(function ($item, $key) {
////            callback fn to reassign values of the array if necessary
////            $comparedLocation = $formattedJobs->map(function ($item, $key) {
//
////                        $this->formattedJobs)
//                foreach($this->formattedJobs as $formattedJob) {
//                    if (($item['startDate'] == $formattedJob['startDate']) && ($item['locations'] == $formattedJob['locations'])) {
////                    if($item['startTime'] == $formattedJob['startTime']) {
//                        echo "<script>console.log( 'Assigned Value: " . $item['locations'] . "' );</script>";
////                        echo "<script>console.log( 'Assigned Value: " . $this->formattedJobs[$this->i]['locations'] . "' );</script>";
//
////                        if($item)
//                        $item['locations'] = "As Above";
//
//                        echo "<script>console.log( 'Assigned Value: " . $item['locations'] . "' );</script>";
//
//                    } else {
//                        $item['locations'] = $item['locations'];
//                    }
////                }
//
//
//                    return $item;
//
//                }
//            });
////        });




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

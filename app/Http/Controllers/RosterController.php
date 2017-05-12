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
        $empList = Employee::all('id', 'first_name');
        $locList = Location::all('id', 'name');

        return view('home/rosters/create')->with(array('empList' => $empList, 'locList' =>$locList));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    TODO: HIGH fix job table to include locations
//TODO: seed job table & location table
//    TODO: assigned_user_id to be changed to assigned_employee_id perhaps. Wait upon User/Employee setup in Web Console
    public function store()
    {
        //            jobs: company_id, assigned_user_id, job_scheduled_start, job_scheduled_end
//        job_locations: job_id, location_id

        //use start date and start time and merge into the one start datetime (and for end datetime)
        $job = new Job;
//       job NOT NULL id, company_id, job_scheduled_start, job_scheduled_end, assigned_user_id


        //insert data into job table
        $job->assigned_user_id = Input::get('employee_id');
        $job->company_id = 1;


        //        $dateSt =
//
//        $timeSt = Input::get('startTime');
        //extract the date and time from the inputs
        $dateStart =Input::get('startDateTxt');//retrieved format = 05/01/2017
        echo "<script>console.log('DateStart input" . $dateStart . "');</script>";
//                echo "<script>console.log( 'Date inputs" . $dateStart . "' );</script>";

//        $carbonDate = Carbon::createFromDate($dateStart);//argument is using now, not the $dateStart
//        echo "<script>console.log('CarbonDate inputs" . $carbonDate . "');</script>";
//        $faker = Faker\Factory::create();
//        $date =  $faker->new DateTime();
//        $date->setDate(2017, 1,1);
//        $date->setTime(10, 00);
        $timeStart =Input::get('startTime');//hh:mm
//        $carbonTime = Carbon::createFromTime($timeStart);
        //concat the date and time
//        $carbonDT = Carbon::create($carbonDate, $cardonTime);
        echo "<script>console.log( 'inputTIme" . $timeStart . "');</script>";
        $dateTimeSt = $dateStart." ".$timeStart;
        echo "<script>console.log( 'date time string " .$dateTimeSt. "');</script>";
        $carbonDT = Carbon::parse($dateTimeSt);
        echo "<script>console.log( 'date time string " .$carbonDT. "');</script>";

        $job->job_scheduled_start = $carbonDT;
        $job->job_scheduled_end = $carbonDT;

        $job->save();
//        $jobId = Job::create($job)->id;
////        if($job->save()) {
////            Response::json(array('success' => true, 'last_insert_id' => $job->id), 200);
////            $jobId = $last_insert_id;
////
////        }
//        //get the job id of the inserted record
////        $jobId = Job::find();
////        $jobId = Job::where('jo', 1)->first();
//
//        //insert data into job_location table
//
//        //job_location NOT NULL job_id, location_id
//        $job_location = new Job_Location;
//        $job_location->location_id = Input::get('location_id');
//        $job_location->job_id = $jobId;
//        $job_location->save();
//
//        $dateSt = Input::get('startDate');
//
//        $timeSt = Input::get('startTime');
//
//        $dts = gettype($dateSt);
//        echo "<script>console.log( 'Date inputs" . $dts . "' );</script>";
//        $dtsTm = gettype($timeSt);
//        echo "<script>console.log( 'Time inputs" . $dtsTm . "' );</script>";
        //roll the date and time into the one dateTime variable
//        $dateTime =
//        $job->job_scheduled_start = $dateTime;
        return view('confirm')->with(array('theData'=> $dateTimeSt, 'theAction' => 'added'));
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
        //
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
        //
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

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
        $checks = collect([0,1,2,3,4,5,6,7,8,9,10]);

        return view('home/rosters/create')->with(array('empList' => $empList, 'locList' =>$locList, 'checks' =>$checks));
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
        $job = new Job;

        //insert data into job table
        $job->assigned_user_id = Input::get('employee_id');
        $job->company_id = 1;

        //extract the date and time from the inputs
        $dateStart =Input::get('startDateTxt');//retrieved format = 05/01/2017
        $timeStart =Input::get('startTime');//hh:mm
        $dateTimeSt = $dateStart." ".$timeStart;
        $carbonStart = Carbon::parse($dateTimeSt);

        //add data to table
        $job->job_scheduled_for = $carbonStart;

        $dateEnd =Input::get('endDateTxt');//retrieved format = 05/01/2017
        $timeEnd =Input::get('endTime');//hh:mm
        $dateTimeEnd = $dateEnd." ".$timeEnd;
        $carbonEnd = Carbon::parse($dateTimeEnd);
//        echo "<script>console.log( 'date time carbon end " .$carbonEnd. "');</script>";

        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM/60);//convert to hours

        //add data to table
        $job->estimated_job_duration = $lengthH;
//        TODO v1 lower or v2: allow input of more than one location for the one create item (better usability)
//        Logic: if one location, just add the one record
//        else if more than one location, need to add same data for the other columns, plus the additional locations
        $job->locations = Input::get('location_name');
        $job->checks = Input::get('checks');
        $job->save();

        return view('confirm')->with(array('theData'=> $job->locations, 'theAction' => 'created a shift for '));
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

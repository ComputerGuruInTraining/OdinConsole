<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp;
use Redirect;

class RosterController extends Controller
{
    //global class variables
    protected $accessToken;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        try {
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->get('http://odinlite.com/public/api/assignedshifts/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $assigned = GuzzleHttp\json_decode((string)$response->getBody());

            foreach($assigned as $i => $item){
                //add the extracted date to each of the objects
                $s = $item->start;
                $sdt = new DateTime($s);
                $sdate = $sdt->format('m/d/Y');
                $stime = $sdt->format('H:i:s');

                $e = $item->end;
                $edt = new DateTime($e);
                $edate = $edt->format('m/d/Y');
                $etime = $edt->format('H:i:s');

                $assigned[$i]->start_date = $sdate;
                $assigned[$i]->start_time = $stime;
                $assigned[$i]->end_time = $etime;

                //save date and location into a new object property for later use (ie to reject duplicate values for the view)
                $assigned[$i]->unique_date = $assigned[$i]->start_date;
                $assigned[$i]->unique_locations = $assigned[$i]->location;
            }

            //pass data to compareValues function in order to only display unique data for each date, rather than duplicating the date and the time when they are duplicate values
            $assigned = $this->compareValues($assigned, 'start_date', 'unique_date',
                'unique_locations', 'checks', 'start_time', 'end_time');

            //change to collection datatype from array for using groupBy fn
            $assigned = collect($assigned);

            //group by date for better view
            $assigned = $this->groupByDate($assigned);

            return view('home/rosters/index')->with(array('assigned' => $assigned, 'rosters' => 'rosters'));
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            //rather than displaying an error page, redirect users to dashboard/login page (preferable)
            return view('admin_template');
        }
    }

    public function oauth(){
        $client = new GuzzleHttp\Client;

        try {
            $response = $client->post('http://odinlite.com/public/oauth/token', [
                'form_params' => [
                    'client_id' => 2,
                    // The secret generated when you ran: php artisan passport:install
                    'client_secret' => 'q41fEWYFbMS6cU6Dh63jMByLRPYI4gHDj13AsjoM',
                    'grant_type' => 'password',
                    'username' => 'bernadettecar77@hotmail.com',
                    'password' => 'password',
                    'scope' => '*',
                ]
            ]);

            $auth = json_decode((string)$response->getBody());

            $this->accessToken = $auth->access_token;

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return view('admin_template');
        }
    }

    public function accessToken(){
        return $this->accessToken;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        try {
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->get('http://odinlite.com/public/api/users/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $employees = GuzzleHttp\json_decode((string)$response->getBody());

            $response2 = $client->get('http://odinlite.com/public/api/locations/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $locations = GuzzleHttp\json_decode((string)$response2->getBody());

            return view('home/rosters/create')->with(array('empList' => $employees, 'locList' => $locations));

        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            //rather than displaying an error page, redirect users to dashboard/login page (preferable)
            return Redirect::to('/rosters');
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
        try {

            $this->oauth();

            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->get('http://odinlite.com/public/api/assignedshifts/'.$id.'/edit', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $assigned = GuzzleHttp\json_decode((string)$response->getBody());

            $responseUsers = $client->get('http://odinlite.com/public/api/users/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $employees = GuzzleHttp\json_decode((string)$responseUsers->getBody());

            $responseLocs = $client->get('http://odinlite.com/public/api/locations/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $locations = GuzzleHttp\json_decode((string)$responseLocs->getBody());

            $assigned = collect($assigned);
            $locationsUnique = $assigned->unique('location_id');
            $employeesUnique = $assigned->unique('mobile_user_id');

            $carbonStart = Carbon::createFromFormat('Y-m-d H:i:s', $assigned[0]->start, 'America/Chicago');
            $carbonEnd = Carbon::createFromFormat('Y-m-d H:i:s', $assigned[0]->end, 'America/Chicago');
            $startDate = $carbonStart->format('m/d/Y');
            $startTime = ((string)$carbonStart->format('H:i'));
            $endDate = $carbonEnd->format('m/d/Y');
            $endTime = ((string)$carbonEnd->format('H:i'));

            return view('home/rosters/edit')->with(array('empList' => $employees,
                'locList' => $locations,
                'assigned' => $assigned,
                'myLocations' => $locationsUnique,
                'myEmployees' => $employeesUnique,
                'startDate' => $startDate,
                'startTime' => $startTime,
                'endDate' => $endDate,
                'endTime' => $endTime
            ));

        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
                echo $e;
                return Redirect::to('/rosters');
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
            $this->validate($request, [
                //TODO: v1 after MPV or v2. atm, if nothing is selected by the user, the default item is added to db. Should be no change if nothing selected for that field. same for locations.
                'employees' => 'required',
                'locations' => 'required',
                'startDateTxt' => 'required',
                'startTime' => 'required',
                'endDateTxt' => 'required',
                'endTime' => 'required'
            ]);

            $locations = Input::get('locations');
            $employees = Input::get('employees');

            $checks = Input::get('checks');

//            //get data from form for non laravel validated inputs
            $dateStart = Input::get('startDateTxt');//retrieved format = 05/01/2017
            $timeStart = Input::get('startTime');//hh:mm
            $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
            $timeEnd = Input::get('endTime');//hh:mm

            //process start date and time before adding to db
            $strStart = $this->jobDateTime($dateStart, $timeStart);
            $strEnd = $this->jobDateTime($dateEnd, $timeEnd);

            $title = 'Security at Several Locations';
            $desc = 'Provide security services at several locations throughout Austin';
            $roster_id = 1;
            $added_id = 1;

            //request to api
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->put('http://odinlite.com/public/api/assignedshifts/'.$id.'/edit', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array('checks' => $checks, 'start' => $strStart,
                        'end' => $strEnd, 'roster_id' => $roster_id, 'title' => $title, 'desc' => $desc,
                        'added_id' => $added_id, 'employees' => $employees, 'locations' => $locations

                    )
                )
            );

            $updated = json_decode((string)$response->getBody());

            $theAction = 'You have successfully edited the shift';
            return view('confirm')->with(array('theAction' => $theAction));
        }
        catch(GuzzleHttp\Exception\BadResponseException $e){
            echo $e;
            return Redirect::to('/rosters/'.$id.'/edit');
        }
        catch(\ErrorException $error){
            echo $error;
            return Redirect::to('/rosters/'.$id.'/edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     * Called from the confirm-delete page
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->delete('http://odinlite.com/public/api/assignedshift/'.$id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,//TODO: Access_token saved for global use
                ]
            ]);

            $theAction = 'You have successfully deleted the shift';
            return view('confirm')->with('theAction', $theAction);

        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/rosters');
        }

    }


    public function groupByDate($assigned)
    {
        //group the collection by startDate for grouping as tbody in the view
        $groupedAssigned = $assigned->groupBy('start_date');
        return $groupedAssigned;
    }

//function defined for global use
    public function compareValues($jobs, $date, $uniqueDate, $uniqueLocations, $checks, $startTime, $endTime)
    {
            for ($i = 0; $i < count($jobs); $i++) {
                for ($j = 0; $j < count($jobs); $j++) {

                    //if startDate the same, preserve the startDate values for future comparisons and use:
                    //and add null to the uniqueDate field which was assigned the values in the startDate field previously,
                    if ($jobs[$i]->$date == $jobs[$j]->$date) {

                        if ($i > $j) {
                            $jobs[$i]->$uniqueDate = null;
                        }
                        //if locations and checks and startTime and endTime the same,
                        //change values of these fields to null for the duplicates:
                        if (($jobs[$i]->$uniqueLocations == $jobs[$j]->$uniqueLocations)
                            &&($jobs[$i]->$uniqueLocations != "Location not in database")
                            && ($jobs[$i]->$checks == $jobs[$j]->$checks)
                            && ($jobs[$i]->$startTime == $jobs[$j]->$startTime)
                            && ($jobs[$i]->$endTime == $jobs[$j]->$endTime)) {
                            if ($i > $j) {
                                $jobs[$i]->$startTime = null;
                                $jobs[$i]->$endTime = null;
                                $jobs[$i]->$uniqueLocations = null;
                                $jobs[$i]->$checks = null;
                            }
                            //if only locations and checks the same, then:
                        } else if (($jobs[$i]->$uniqueLocations == $jobs[$j]->$uniqueLocations)
                            &&($jobs[$i]->$uniqueLocations != "Location not in database")
                            && ($jobs[$i]->$checks == $jobs[$j]->$checks)
                        ) {
                            if ($i > $j) {
                                $jobs[$i]->$uniqueLocations = null;
                                $jobs[$i]->$checks = null;
                            }
                        }
                    }
                }
            }
        return $jobs;
    }
    public function formData($request){
        //data for validation
        $locationArray = $request->input('locations');
        $employeeArray = $request->input('employees');
        $checks = Input::get('checks');
        //  $companyId = 1;

        //hardcoded values TODO: user input
        $title = 'Security at Several Locations';
        $desc = 'Provide security services at several locations throughout Austin';
        $roster_id = 1;
        $added_id = 1;

        //get data from form for non laravel validated inputs
        $dateStart = $request->input('startDateTxt');
//        $dateStart = Input::get('startDateTxt');//retrieved format = 05/01/2017
        $timeStart = Input::get('startTime');//hh:mm
        $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
        $timeEnd = Input::get('endTime');//hh:mm

        //process start date and time before adding to db
        $strStart = $this->jobDateTime($dateStart, $timeStart);
        $strEnd = $this->jobDateTime($dateEnd, $timeEnd);


        $this->oauth();

        //retrieve token needed for authorized http requests
        $token = $this->accessToken();

        $client = new GuzzleHttp\Client;

        $response = $client->post('http://odinlite.com/public/api/assignedshifts', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ),
                'json' => array(
                    'checks' => $checks, 'start' => $strStart,
                    'end' => $strEnd, 'roster_id' => $roster_id, 'title' => $title, 'desc' => $desc,
                    'added_id' => $added_id, 'employees' => $employeeArray, 'locations' => $locationArray
                )
            )
        );

        $assigned = GuzzleHttp\json_decode((string)$response->getBody());

        return $dateStart;
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

    //pm format $date = dd/mm/yyyy, $time = HH:MM
    public function jobDateTime($date, $time)
    {
        $y = substr($date, 6, 4);
        $m = substr($date, 3, 2);
        $d = substr($date, 0, 2);

        $dtStr = $y."-".$m."-".$d . " " . $time.':00';

        return $dtStr;
    }

    public function jobDuration($carbonStart, $carbonEnd)
    {
        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM / 60);//convert to hours
        return $lengthH;
    }

}

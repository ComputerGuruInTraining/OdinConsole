<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp;
use GuzzleHttp\Exception;
use GuzzleHttp\Client;
use Redirect;
use Config;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\View;

//FIXME: dates month and day mixed up with formatting
class RosterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $locOld;
    public $startTimeOld;

    public function index()
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $companyId = session('compId');

                $response = $client->get(Config::get('constants.API_URL') . 'assignedshifts/list/' . $companyId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $assigned = GuzzleHttp\json_decode((string)$response->getBody());

                foreach ($assigned as $i => $item) {
                    //add the extracted date to each of the objects and format date
                    $s = $item->start;
                    $sdt = new DateTime($s);
                    $sdate = $sdt->format('m/d/Y');
                    $stime = $sdt->format('g.i a');
                    $e = $item->end;
                    $edt = new DateTime($e);
                    $edate = $edt->format('m/d/Y');
                    $etime = $edt->format('g.i a');

                    $assigned[$i]->start_date = $sdate;
                    $assigned[$i]->start_time = $stime;
                    $assigned[$i]->end_time = $etime;
                    //save date and location into a new object property for later use (ie to reject duplicate values for the view)
                    $assigned[$i]->unique_date = $assigned[$i]->start_date;
                    $assigned[$i]->unique_locations = $assigned[$i]->location;
                    $assigned[$i]->unique_employees = $assigned[$i]->employee;

                }

                //pass data to compareValues function in order to only display unique data for each date, rather than duplicating the date and the time when they are duplicate values
                $assigned = $this->compareValues($assigned);

                //display as midnight if time == 12am
                foreach ($assigned as $i => $item) {

                    $assigned[$i]->start_time = timeMidnight($assigned[$i]->start_time);
                    $assigned[$i]->end_time = timeMidnight($assigned[$i]->end_time);

                }

                //change to collection datatype from array for using groupBy fn
                $assigned = collect($assigned);

                //group by date for better view
                $assigned = $this->groupByShift($assigned);

                return view('home/rosters/index')->with(array('assigned' => $assigned, 'url' => 'rosters'));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying shifts';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying shift details';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display shift details';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading shifts';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        try {

            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');
                $compId = session('compId');

                $client = new GuzzleHttp\Client;

                $response = $client->get(Config::get('constants.API_URL') . 'employees/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $employees = GuzzleHttp\json_decode((string)$response->getBody());

                $response2 = $client->get(Config::get('constants.API_URL') . 'locations/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $locations = GuzzleHttp\json_decode((string)$response2->getBody());

                //sort the lists before sending to view
                $locations = array_sort($locations, 'name', SORT_ASC);

                $employees = array_sort($employees, 'last_name', SORT_ASC);

                return view('home/rosters/create')->with(array('empList' => $employees, 'locList' => $locations));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying add shift page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying add shift form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying add shift';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading add shift page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //error catching has been tested

    public function store(Request $request)
    {
        //TODO: improve. atm, if nothing is selected by the user, the default item is added to db. same for locations?? true still??
        try {

            $this->validate($request, [
                'title' => 'required|max:255',
                'desc' => 'max:255',
                'employees' => 'required',
                'locations' => 'required',
                'startDateTxt' => 'required',
                'startTime' => 'required',
                'endDateTxt' => 'required',
                'endTime' => 'required',
                'checks' => 'digits_between:1,9'
            ]);

            //get the data from the form and perform necessary calculations prior to inserting into db
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                try {
                    $dateStart = $this->formData($request);

                    if ($dateStart == 'error') {

                        $e = 'Error storing shift details';
//                        $errors = collect($e);
                        return Redirect::to('rosters/create')
                            ->withInput()
                            ->withErrors($e);
                    } else {
                        return view('confirm-create')->with(array('theData' => $dateStart, 'entity' => 'Shift', 'url' => 'rosters'));
                    }

                } catch (\Exception $exception) {
                    return Redirect::to('rosters/create')
                        ->withInput()
                        ->withErrors('Operation failed. Please ensure input valid.');
                }
            } else {
                return Redirect::to('/login');
            }

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('rosters/create')
                ->withInput()
                ->withErrors('Operation failed');

        } catch (\ErrorException $error) {
            return Redirect::to('rosters/create')
                ->withInput()
                ->withErrors('Error storing shift details');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('rosters/create')
                ->withInput()
                ->withErrors('Error storing shift. Please check input is valid.');

        }
        catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        }
    }

    //IMPORTANT: Don't catch in the "helper" function, only catch in the calling function ie store()
    function formData($request)
    {
        $token = session('token');
        $compId = session('compId');

        //TODO: rosters for a date-range
        $roster_id = 1;

        //data for validation
        $locationArray = $request->input('locations');
        $employeeArray = $request->input('employees');
        $checks = Input::get('checks');
        $title = $request->input('title');
        $desc = $request->input('desc');

        //get data from form for non laravel validated inputs
        $dateStart = $request->input('startDateTxt');
        $timeStart = Input::get('startTime');//hh:mm
        $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
        $timeEnd = Input::get('endTime');//hh:mm

        //account for situation where checks is disabled and value is null
        //but value should be 1 as only disabled when 1 location and therefore 1 check
        if ($checks == null) {

            $checks = 1;
        }

        //process start date and time before adding to db
        //function in functions.php
        $strStart = jobDateTime($dateStart, $timeStart);
        $strEnd = jobDateTime($dateEnd, $timeEnd);

        $client = new GuzzleHttp\Client;

        $response = $client->post(Config::get('constants.API_URL') . 'assignedshifts', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ),
                'json' => array(
                    'checks' => $checks, 'start' => $strStart,
                    'end' => $strEnd, 'roster_id' => $roster_id, 'title' => $title, 'desc' => $desc,
                    'comp_id' => $compId, 'employees' => $employeeArray, 'locations' => $locationArray
                )
            )
        );

        $assigned = GuzzleHttp\json_decode((string)$response->getBody());

        if ($assigned->success == true) {
            return $dateStart;
        } else {
            return 'error';
        }
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
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');
                $compId = session('compId');

                $client = new GuzzleHttp\Client;

                $response = $client->get(Config::get('constants.API_URL') . 'assignedshifts/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $assigned = GuzzleHttp\json_decode((string)$response->getBody());

                $responseUsers = $client->get(Config::get('constants.API_URL') . 'employees/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $employees = GuzzleHttp\json_decode((string)$responseUsers->getBody());

                $responseLocs = $client->get(Config::get('constants.API_URL') . 'locations/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $locations = GuzzleHttp\json_decode((string)$responseLocs->getBody());

                $assigned = collect($assigned);

                //to populate the select lists with the locations and employees currently assigned to the shift
                $locationsUnique = $assigned->unique('location_id');
                $employeesUnique = $assigned->unique('mobile_user_id');

                $empNotSelected = $this->nonSelectedList($employeesUnique, $employees,
                    'mobile_user_id', 'user_id');

//                dd($locationsUnique, $locations, $employeesUnique, $employees);
                $locNotSelected = $this->nonSelectedList($locationsUnique, $locations,
                    'location_id', 'id');

                //sort the lists before sending to view
                $locNotSelected = array_sort($locNotSelected, 'name', SORT_ASC);

                $empNotSelected = array_sort($empNotSelected, 'last_name', SORT_ASC);

                $employees = array_sort($employees, 'last_name', SORT_ASC);

                $locations = array_sort($locations, 'name', SORT_ASC);

                //format dates
                $carbonStart = Carbon::createFromFormat('Y-m-d H:i:s', $assigned[0]->start);

                $carbonEnd = Carbon::createFromFormat('Y-m-d H:i:s', $assigned[0]->end);
                $startDate = $carbonStart->format('m/d/Y');
                $startTime = ((string)$carbonStart->format('H:i'));
                $endDate = $carbonEnd->format('m/d/Y');
                $endTime = ((string)$carbonEnd->format('H:i'));

                return view('home/rosters/edit')->with(array(
                    'empList' => $empNotSelected,
                    'locList' => $locNotSelected,
                    'assigned' => $assigned,
                    'myLocations' => $locationsUnique,
                    'myEmployees' => $employeesUnique,
                    'startDate' => $startDate,
                    'startTime' => $startTime,
                    'endDate' => $endDate,
                    'endTime' => $endTime,
                    'locationsAll' => $locations,
                    'employeesAll' => $employees
                ));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying edit shift page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying edit shift form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying edit shift';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit shift page';
            return view('error-msg')->with('msg', $error);
        }

    }

    public function nonSelectedList($selected, $list, $propertySelected, $propertyList)
    {

        $collectSelect = collect($selected);

        $collectPropertySelected = $collectSelect->pluck($propertySelected);

        $collect = collect($list);

        $collectPropertyList = $collect->pluck($propertyList);

        //employees and locations that are not selected for the assigned_shift
        $nonAsg = $collectPropertyList->diff($collectPropertySelected);

        $notSelected = collect([]);

        foreach ($nonAsg as $non) {
            foreach ($list as $item) {
                if ($non == $item->$propertyList) {
                    $notSelected->push($item);
                }
            }
        }

        return $notSelected;
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
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');
                $compId = session('compId');

                $this->validate($request, [
                    //TODO: v1 after MPV or v2. atm, if nothing is selected by the user, the default item is added to db.?? true still??
                    // Should be no change if nothing selected for that field. same for locations.
                    'title' => 'required|max:255',
                    'desc' => 'max:255',
                    'employees' => 'required',
                    'locations' => 'required',
                    'startDateTxt' => 'required',
                    'startTime' => 'required',
                    'endDateTxt' => 'required',
                    'endTime' => 'required',
                    'checks' => 'digits_between:1,9'
                ]);

                //get user input
                $locations = Input::get('locations');
                $employees = Input::get('employees');
                $checks = Input::get('checks');
                $dateStart = Input::get('startDateTxt');//retrieved format = 05/01/2017
                $timeStart = Input::get('startTime');//hh:mm
                $dateEnd = Input::get('endDateTxt');//retrieved format = 05/01/2017
                $timeEnd = Input::get('endTime');//hh:mm
                $title = Input::get('title');
                $desc = Input::get('desc');

                //account for situation where checks is disabled and value is null
                //but value should be 1 as only disabled when 1 location and therefore 1 check
                if ($checks == null) {

                    $checks = 1;
                }

                if($desc == null){
                    //sending through null doesn't successfully update the record.
                    $desc = "none";
//                    dd($desc);

                }

                //process start date and time before adding to db
                $strStart = jobDateTime($dateStart, $timeStart);
                $strEnd = jobDateTime($dateEnd, $timeEnd);

                //TODO: create roster and auto-populate id
                $roster_id = 1;

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'assignedshifts/' . $id . '/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json',
                            'X-HTTP-Method-Override' => 'PUT'
                        ),
                        'json' => array('checks' => $checks, 'start' => $strStart,
                            'end' => $strEnd, 'roster_id' => $roster_id, 'title' => $title, 'desc' => $desc,
                            'compId' => $compId, 'employees' => $employees, 'locations' => $locations

                        )
                    )
                );

                $updated = json_decode((string)$response->getBody());

                $theAction = 'You have successfully edited the shift';
                return view('confirm')->with(array('theAction' => $theAction));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('/rosters/' . $id . '/edit')
                ->withInput()
                ->withErrors('Operation failed');

        } catch (\ErrorException $error) {
            return Redirect::to('/rosters/' . $id . '/edit')
                ->withInput()
                ->withErrors('Error updating shift details');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/rosters/' . $id . '/edit')
                ->withInput()
                ->withErrors('Error updating shift. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('/');
//                ->withErrors('Session expired. Please login.');todo: include error msg when the redirect is working.
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
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'assignedshift/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'
                    ]
                ]);

                $theAction = 'You have successfully deleted the shift';
                return view('confirm')->with('theAction', $theAction);
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error deleting shift. Error code: BadResponseException';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting shift. Error code: ErrorException';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error deleting shift. Error code: Exception';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error deleting shift. Error code: InvalidArgumentException';
            return view('error-msg')->with('msg', $error);
        }
    }

    //TODO: remove function groupByShift and just have groupBy, previously a longer function was used
    public function groupByShift($assigned)
    {
        //group the collection by startDate for grouping as tbody in the view
        $groupedAssigned = $assigned->groupBy('assigned_shift_id');
        return $groupedAssigned;
    }

//function defined for global use
    public function compareValues($jobs)
    {
        for ($i = 0; $i < count($jobs); $i++) {
            for ($j = 0; $j < count($jobs); $j++) {

                //if startDate & shift time the same, preserve the startDate values for future comparisons and use:
                //and add null to the uniqueDate field which was assigned the values in the startDate field previously,
                if (($jobs[$i]->start_date == $jobs[$j]->start_date)
                    && ($jobs[$i]->start_time == $jobs[$j]->start_time)
                    && ($jobs[$i]->end_time == $jobs[$j]->end_time)
                ) {

                    if ($i > $j) {
                        $jobs[$i]->unique_date = null;
                        $jobs[$i]->start_time = null;
                        $jobs[$i]->end_time = null;
                    }
                    //if locations and checks and startTime and endTime the same,
                    //change values of these fields to null for the duplicates:
                    if (($jobs[$i]->unique_locations == $jobs[$j]->unique_locations)
                        && ($jobs[$i]->checks == $jobs[$j]->checks)
                    ) {
                        if ($i > $j) {
                            $jobs[$i]->start_time = null;
                            $jobs[$i]->end_time = null;
                            $jobs[$i]->unique_locations = null;
                            $jobs[$i]->checks = null;
                        }
                        //if only locations and checks the same, then:
                    } else if (($jobs[$i]->unique_locations == $jobs[$j]->unique_locations)
                        && ($jobs[$i]->checks == $jobs[$j]->checks)
                    ) {
                        if ($i > $j) {
                            $jobs[$i]->unique_locations = null;
                            $jobs[$i]->checks = null;
                        }
                    } else if ($jobs[$i]->unique_employees == $jobs[$j]->unique_employees) {
                        if ($i > $j) {
                            $jobs[$i]->unique_employees = null;
                        }

                    }

                }
            }
        }
        return $jobs;
    }
}

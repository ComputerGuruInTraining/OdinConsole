<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Redirect;
use Carbon\Carbon;
use Input;
use DateTime;
use Config;
use PDF;
use App;

class ReportController extends Controller
{
//    protected $accessToken;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //error catching to be tested
    public function index()
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->get(Config::get('constants.API_URL') . 'reports/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $reports = json_decode((string)$response->getBody());

//                dd($reports);
                if (count($reports) > 0) {
                    foreach ($reports as $i => $item) {
                        //add the extracted date to each of the objects and format date
                        $s = $item->date_start;

                        $sdt = new DateTime($s);
                        $sdate = $sdt->format('m/d/Y');

                        $e = $item->date_end;

                        $edt = new DateTime($e);
                        $edate = $edt->format('m/d/Y');

                        $reports[$i]->form_start = $sdate;
                        $reports[$i]->form_end = $edate;
                    }

                }

                return view('report/reports')->with(array(
                    'reports' => $reports,
                    'url' => 'reports'
                ));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying reports';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying generated reports';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying report details';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading reports';
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
        //need to pass through locations and report_types for the select lists.
        //therefore need to gather this data from the api
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests

                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->get(Config::get('constants.API_URL') . 'locations/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $locations = json_decode((string)$response->getBody());

                return view('report/create')->with('locations', $locations);

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying add report page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying add report form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying add report';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading add report page';
            return view('error-msg')->with('msg', $error);
        }

//        return view('report/create')->with();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (session()->has('token')) {

                //validate data
                $this->validate($request, [
                    'dateFrom' => 'required',
                    'dateTo' => 'required',
                    'location' => 'required',
                    'type' => 'required',
                ]);

                //get data from form
                $location = $request->input('location');
                $type = $request->input('type');

                $dateFromStr = Input::get('dateFrom');
                $dateToStr = Input::get('dateTo');

                //convert date strings from mm/dd/yyyy to dd-mm-yyyy, retain string format
                $dateFrom = jobDateTime($dateFromStr, "00:00");//start of the day
                $dateTo = jobDateTime($dateToStr, "23:59");//end of the day

                $token = session('token');
                $compId = session('compId');

                //post to api via function which calls a different route based on report type
                if ($type == 'Case Notes') {
                    $result = $this->postCaseNote($location, $type, $dateFrom, $dateTo, $token, $compId);
                } else if ($type == 'Location Checks') {
                    $result = $this->postCasesChecks($location, $type, $dateFrom, $dateTo, $token, $compId);
                }

                if ($result->success == true) {
                    return view('confirm-create-general')
                        ->with(array(
                            'theMsg' => 'The report has been successfully generated',
                            'btnText' => 'Generate Report',
                            'url' => 'reports'
                        ));
                }
// else if ($result->success == false) {
////                    dd("false :)");//yep, echoes that
//                    $msg = 'A report was not generated as there is no shift data at this location for the specified period.';
//                    return view('error-msg')->with('msg', $msg);
//                }
                else {
                    $msg = 'A report was not generated for the location as there is no report data for the period.';
                    return view('error-msg')->with('msg', $msg);
                }

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('reports/create')
                ->withInput()
                ->withErrors('Operation failed.');
        } catch (\ErrorException $error) {
            return Redirect::to('reports/create')
                ->withInput()
                ->withErrors('Error generating report');
        } catch (\InvalidArgumentException $err) {
            return Redirect::to('reports/create')
                ->withInput()
                ->withErrors('Error generating report. Please check input is valid.');
        } catch (\Exception $exception) {
            return Redirect::to('reports/create')
                ->withInput()
                ->withErrors('Operation failed. Please ensure input valid.');
        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        }
    }

    /**
     * Store a report of type/activity = Case Notes
     *
     * @param  request variables gathered from input
     * @return \Illuminate\Http\Response
     */
    public function postCaseNote($location, $type, $dateFrom, $dateTo, $token, $compId)
    {
        $client = new GuzzleHttp\Client;

        $response = $client->post(Config::get('constants.API_URL') . 'reports/casenotes', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ),
                'json' => array(
                    'location' => $location, 'type' => $type,
                    'dateFrom' => $dateFrom, 'dateTo' => $dateTo,
                    'compId' => $compId
                )
            )
        );

        $result = GuzzleHttp\json_decode((string)$response->getBody());

        return $result;
    }

    /**
     * Store a report of type/activity = Case Notes and Location Checks
     *
     * @param  request variables gathered from input
     * @return \Illuminate\Http\Response
     */
    public function postCasesChecks($location, $type, $dateFrom, $dateTo, $token, $compId)
    {

        $client = new GuzzleHttp\Client;

        $response = $client->post(Config::get('constants.API_URL') . 'reports/casesandchecks', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ),
                'json' => array(
                    'location' => $location, 'type' => $type,
                    'dateFrom' => $dateFrom, 'dateTo' => $dateTo,
                    'compId' => $compId
                )
            )
        );

        $result = GuzzleHttp\json_decode((string)$response->getBody());

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;
//                $id = 24;

                $response = $client->get(Config::get('constants.API_URL') . 'report/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $report = json_decode((string)$response->getBody());

                //format dates to be in the form 3rd January 2107 for report date range
                $sdate = formatDates($report->date_start);

                $edate = formatDates($report->date_end);

                if ($report->type == 'Case Notes') {

                    $cases = $this->getCaseNotes($id, $token);

                    if ($cases != 'errorInResult') {
                        foreach ($cases->reportCaseNotes as $i => $item) {
                            //change to collection datatype from array for using groupBy fn
                            $caseNotes = collect($cases->reportCaseNotes);
                            $groupCases = $caseNotes->groupBy('case_date');
                        }

                        view()->share(array('cases' => $cases,
                            'groupCases' => $groupCases,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate
                        ));

                        return view('report/case_notes/show');

                    } else {
                        $err = 'There were no case notes created during the period that the selected report covers.';
                        $errors = collect($err);
                        return Redirect::to('/reports')->with('errors', $errors);
                    }
                } else if ($report->type == 'Location Checks') {

                    $checks = $this->getLocationChecks($id, $token);

                    //ie success == false
                    if ($checks != 'errorInResult') {

//                        $groupShiftChecks = $this->formatLocationChecksData($checks);
                        $collectChecks = $this->formatLocationChecksData($checks);

                        //number of check ins at premise
                        $checkIns = $collectChecks->pluck('check_ins');

                        $total = $checkIns->count();

                        //group by date for better view
                        $groupShiftChecks = $collectChecks->groupBy('dateTzCheckIn');

                        view()->share(array(
                            'shiftChecks' => $groupShiftChecks,
                            'location' => $checks->location,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate,
                            'total' => $total
                        ));

                        return view('report/location_checks/show');

                    } else {
                        //TODO: test me
                        $err = 'There were no location checks during the period that the selected report covers.';
                        $errors = collect($err);
                        return Redirect::to('/reports')->with('errors', $errors);
                    }
                }

            } else {
                //ie no session token exists and therefore the user is not authenticated

                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying report';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying report details';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error loading report';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading report details';
            return view('error-msg')->with('msg', $error);
        }

//        catch (GuzzleHttp\Exception\BadResponseException $e) {
//            //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
//            $msg = 'Error exception displaying report';
//            return view('error-msg')->with('msg', $msg);
//        } catch (\ErrorException $error) {
//            $msg = 'Error exception displaying report on webpage';
//            return view('error-msg')->with('msg', $msg);
//        }

    }

    //returns a collection
    public function formatLocationChecksData($checks)
    {
        //format check in timestamp to be a user friendly date and time which accounts for the timezone
        foreach ($checks->shiftChecks as $i => $item) {
//TODO: consider adding an endnote to report advising of this info

            $no_data = '';
            //constant
//            define("$no_data", 0);
//          check ins
//         if there is a value for the check_in datetime (ie check_ins property) therefore there is a record
            //(because of check_ins datetime is a default value for the field)
            if ($item->check_ins != null) {

                $tzDT = $this->viaTimezone($item->checkin_latitude,
                    $item->checkin_longitude,
                    $checks->location->latitude,
                    $checks->location->longitude, $item->check_ins);

                $checks->shiftChecks[$i]->dateTzCheckIn = $tzDT->get('date');
                $checks->shiftChecks[$i]->timeTzCheckIn = $tzDT->get('time');

                //if there is geoLocation data for the location check
                if (($item->checkin_latitude != "") && ($item->checkin_longitude != "")) {

                    $distance = distance($item->checkin_latitude, $item->checkin_longitude,
                        $checks->location->latitude, $checks->location->longitude);
//                    $distance = distance($item->checkin_latitude, $item->checkin_longitude, -35.381536, 149.058894);// testing

                    $result = geoRange($distance);

                    $checks->shiftChecks[$i]->withinRange = $result;

                    if ($item->withinRange == 'yes') {

                        $checks->shiftChecks[$i]->img = 'if_checkmark-g_86134';
                    } elseif ($item->withinRange == 'ok') {
                        $checks->shiftChecks[$i]->img = 'if_checkmark-o_86136';
                    } elseif ($item->withinRange == 'no') {
                        $checks->shiftChecks[$i]->img = 'if_cross_5233';
                    }

                    //for testing purposes only: 1

//                    $distance = distance($item->checkin_latitude, $item->checkin_longitude, -35.381536, 149.058894);// testing


                } else {
                    //for testing purposes only: 1
//                 $distance = distance($checks->location->latitude, $checks->location->longitude, $checks->location->latitude, $checks->location->longitude);//should return 0.0km
                    $checks->shiftChecks[$i]->withinRange = "-";

                    $checks->shiftChecks[$i]->img = 'if_minus_216340';

                    //for testing purposes only: 2

//                    $distance2 = distance(-35.381536, 149.058894, $checks->location->latitude, $checks->location->longitude);//should return 0.0km

                    //
                    //Latitude -35.381536
//                    longitude: 149.058894


//                    $checks->shiftChecks[$i]->withinRange = $distance + " should equal 0 as same location";
//                    $checks->shiftChecks[$i]->withinRange = $distance2 + " not gathered";

                }


            } else {

                $checks->shiftChecks[$i]->dateTzCheckIn = $no_data;
                $checks->shiftChecks[$i]->timeTzCheckIn = $no_data;

            }

//           check outs
            //if there is a value for the check_out datetime (ie check_outs property)

            if ($item->check_outs != null) {
                $tz = $this->viaTimezone($item->checkout_latitude,
                    $item->checkout_longitude,
                    $checks->location->latitude,
                    $checks->location->longitude, $item->check_outs);

                $checks->shiftChecks[$i]->dateTzCheckOut = $tz->get('date');
                $checks->shiftChecks[$i]->timeTzCheckOut = $tz->get('time');

            } else {
                $checks->shiftChecks[$i]->dateTzCheckOut = $no_data;
                $checks->shiftChecks[$i]->timeTzCheckOut = $no_data;
            }


        }

//        //number of check ins at premise
//        //change to collection datatype from array for using groupBy fn and count
        $collectChecks = collect($checks->shiftChecks);
//
//        $checkIns = $collectChecks->pluck('check_ins');
//
//        $total = $checkIns->count();
//
////        $checksCollection = collect($checks->shiftChecks);
//
//        //group by date for better view
//        $groupShiftChecks = $collectChecks->groupBy('dateTzCheckIn');

        return $collectChecks;
//        return $groupShiftChecks;
    }

    function viaTimezone($geoLatitude, $geoLongitude, $locLatitude, $locLongitude, $dateTime)
    {
        //if there is geoLocation data for the location check
        if (($geoLatitude != "") && ($geoLongitude != "")) {

            $lat = $geoLatitude;
            $long = $geoLongitude;

        } else {
            //else use the location for the location check
            //TODO: consider adding an endnote to report advising of this info
            $lat = $locLatitude;
            $long = $locLongitude;
        }

        $tzDT = timezoneDT($lat, $long, $dateTime);

        return $tzDT;


    }

    /**
     * Display the specified resource in a basic layout for download
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;
//                $id = 24;

                $response = $client->get(Config::get('constants.API_URL') . 'report/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $report = json_decode((string)$response->getBody());

                //format dates to be in the form 3rd January 2107 for report date range
                $sdate = formatDates($report->date_start);

                $edate = formatDates($report->date_end);

                if ($report->type == 'Case Notes') {

                    $cases = $this->getCaseNotes($id, $token);

                    if ($cases != 'errorInResult') {
                        foreach ($cases->reportCaseNotes as $i => $item) {
                            //change to collection datatype from array for using groupBy fn
                            $caseNotes = collect($cases->reportCaseNotes);
                            $groupCases = $caseNotes->groupBy('case_date');
                        }

                        view()->share(array('cases' => $cases,
                            'groupCases' => $groupCases,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate
                        ));

                        if ($request->has('download')) {
                            // pass view file
                            $pdf = PDF::loadView('report/case_notes/pdf')->setPaper('a4', 'landscape');
                            // download pdf w current date in the name
                            $dateTime = Carbon::now();
                            $date = substr($dateTime, 0, 10);
                            return $pdf->download('Case Notes Report ' . $date . '.pdf');
                        }

                        return view('report/case_notes/pdf');
                    } else {
                        $err = 'There were no case notes created during the period that the selected report covers.';
                        $errors = collect($err);
                        return Redirect::to('/reports')->with('errors', $errors);
                    }
                } else if ($report->type == 'Location Checks') {
                    $checks = $this->getLocationChecks($id, $token);

                    //ie success == false
                    if ($checks != 'errorInResult') {

//                        $groupShiftChecks = $this->formatLocationChecksData($checks);
                        $collectChecks = $this->formatLocationChecksData($checks);

                        //number of check ins at premise
                        $checkIns = $collectChecks->pluck('check_ins');

                        $total = $checkIns->count();

                        //group by date for better view
                        $groupShiftChecks = $collectChecks->groupBy('dateTzCheckIn');

                        view()->share(array(
                            'shiftChecks' => $groupShiftChecks,
                            'location' => $checks->location,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate,
                            'total' => $total
                        ));

                        if ($request->has('download')) {
                            // pass view file
                            $pdf = PDF::loadView('report/location_checks/pdf')->setPaper('a4', 'landscape');
                            // download pdf w current date in the name
                            $dateTime = Carbon::now();
                            $date = substr($dateTime, 0, 10);
                            return $pdf->download('Location Checks Report ' . $date . '.pdf');
                        }

                        return view('report/location_checks/pdf');

                    } else {
                        //TODO: test me
                        $err = 'There were no location checks during the period that the selected report covers.';
                        $errors = collect($err);
                        return Redirect::to('/reports')->with('errors', $errors);
                    }

                }

            } else {
                //ie no session token exists and therefore the user is not authenticated

                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying report';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying report details';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error loading report';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading report details';
            return view('error-msg')->with('msg', $error);
        }
    }

    public function getLocationChecks($id, $token)
    {
        try {
            $client = new GuzzleHttp\Client;

            $response = $client->get(Config::get('constants.API_URL') . 'reportchecks/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $checks = json_decode((string)$response->getBody());

            if ($checks->success == false) {
                return 'errorInResult';
//
            } else {
//                //extract location latitude and longitude to be used to find timezone
//                //for this report atm, case note geoLocation is presumed to be location of premise
//                $lat = $checks->location->latitude;
//                $long = $checks->location->longitude;
//                //calculate the date and time based on the location and any of the case notes created_at timestamp
//                $collection = timezone($lat, $long, $checks->reportCaseNotes[0]->created_at);
//
//                //format dates to be mm/dd/yyyy for case notes
//                foreach ($checks->reportCaseNotes as $i => $item) {
//                    //add the extracted date to each of the objects and format date to be mm/dd/yyyy
//                    $t = $checks->reportCaseNotes[$i]->created_at;
//
//                    //friendly dates
//                    $dateForTS = date_create($t);
//                    $dateInTS = date_timestamp_get($dateForTS);
//
//                    //google timezone api returns the time in seconds from utc time (rawOffset)
//                    //and a value for if in daylight savings timezone (dstOffset) which will equal 0 if not applicable
//                    $tsUsingResult = $dateInTS + $collection->get('dstOffset') + $collection->get('rawOffset');
//
//                    //convert timestamp to a datetime string
//                    $date = date('m/d/Y', $tsUsingResult);
//
//                    $time = date('g.i a', $tsUsingResult);
//
//                    $checks->reportCaseNotes[$i]->case_date = $date;
//                    $checks->reportCaseNotes[$i]->case_time = $time;

                return $checks;
            }

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
            $msg = 'Error exception displaying report';
            return view('error-msg')->with('msg', $msg);
        } catch (\ErrorException $error) {
            $msg = 'Error exception displaying report on webpage';
            return view('error-msg')->with('msg', $msg);
        }
    }

    public function getCaseNotes($id, $token)
    {
        try {
            $client = new GuzzleHttp\Client;

            $response = $client->get(Config::get('constants.API_URL') . 'reportcases/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $cases = json_decode((string)$response->getBody());

            if ($cases->success == false) {
                return 'errorInResult';

            } else {

                //IMPORTANT: just uses the one latitude and longitude for each calculation
                //because the report is for a single location, therefore the values are always the same
                //see LocationChecks for an example of timezone conversion that uses a different location each time

                //extract location latitude and longitude to be used to find timezone
                //for this report atm, case note geoLocation is presumed to be location of premise
                $lat = $cases->location->latitude;
                $long = $cases->location->longitude;
                //calculate the date and time based on the location and any of the case notes created_at timestamp
                $collection = timezone($lat, $long, $cases->reportCaseNotes[0]->created_at);

                //format dates to be mm/dd/yyyy for case notes
                foreach ($cases->reportCaseNotes as $i => $item) {
                    //add the extracted date to each of the objects and format date to be mm/dd/yyyy
                    $t = $cases->reportCaseNotes[$i]->created_at;

                    //friendly dates
                    $dateForTS = date_create($t);
                    $dateInTS = date_timestamp_get($dateForTS);

                    //google timezone api returns the time in seconds from utc time (rawOffset)
                    //and a value for if in daylight savings timezone (dstOffset) which will equal 0 if not applicable
                    $tsUsingResult = $dateInTS + $collection->get('dstOffset') + $collection->get('rawOffset');

                    //convert timestamp to a datetime string
                    $date = date('m/d/Y', $tsUsingResult);

                    //if img, mark Y TODO: link
                    if ($item->img != "") {
                        $cases->reportCaseNotes[$i]->hasImg = 'Y';
                    } else {
                        $cases->reportCaseNotes[$i]->hasImg = '-';

                    }
//                    $time = date('g.i a', $tsUsingResult);

                    $cases->reportCaseNotes[$i]->case_date = $date;
//                    $cases->reportCaseNotes[$i]->case_time = $time;
                }
                return $cases;
            }

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
            $msg = 'Error exception displaying report';
            return view('error-msg')->with('msg', $msg);
        } catch (\ErrorException $error) {
            $msg = 'Error exception displaying report on webpage';
            return view('error-msg')->with('msg', $msg);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //edit will return the show() but with edit btns next to each case note
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->get(Config::get('constants.API_URL') . 'report/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $report = json_decode((string)$response->getBody());

                if ($report->type == 'Case Notes') {

                    $response = $client->get(Config::get('constants.API_URL') . 'reportcases/' . $id, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token,
                        ]
                    ]);

                    $cases = json_decode((string)$response->getBody());

                    if ($cases->success == false) {
                        $err = 'There were no case notes created during the period that the selected report covers.';
                        $errors = collect($err);
                        return Redirect::to('/reports')->with('errors', $errors);
                    } else {

                        //format dates to be 3rd January 2107 for report date range
                        //  foreach($report as $i => $item){
                        //add the extracted date to each of the objects and format date
                        $s = $report->date_start;

                        $sdt = new DateTime($s);
                        $sdate = $sdt->format('jS F Y');

                        $e = $report->date_end;

                        $edt = new DateTime($e);
                        $edate = $edt->format('jS F Y');

                        //format dates to be mm/dd/yyyy for case notes
                        foreach ($cases->reportCaseNotes as $i => $item) {
                            //add the extracted date to each of the objects and format date
                            $t = $cases->reportCaseNotes[$i]->created_at;

                            $dt = new DateTime($t);
                            $date = $dt->format('m/d/Y');
//                            $time = $dt->format('g.i a');

                            $cases->reportCaseNotes[$i]->case_date = $date;

                            //if img, mark Y TODO: link
                            if ($item->img != "") {
                                $cases->reportCaseNotes[$i]->hasImg = 'Y';
                            } else {
                                $cases->reportCaseNotes[$i]->hasImg = '-';

                            }

//                            $cases->reportCaseNotes[$i]->case_time = $time;

                            //change to collection datatype from array for using groupBy fn
                            $caseNotes = collect($cases->reportCaseNotes);
                            $groupCases = $caseNotes->groupBy('case_date');

                        }

                        $urlCancel = 'reports-' . $id . '-edit';

                        //pass through report id, attach the case note id on the Manage Report Case Notes Page
//                        $urlDel = '/report/'.$id.'/delete/';
//                        $urlDel = 'case-notes';
                        $reportId = $id;

                        return view('report/case_notes/edit')->with(array('cases' => $cases,
                            'groupCases' => $groupCases,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate,
                            'urlCancel' => $urlCancel,
                            'reportId' => $reportId
                        ));


                    }
                }

                /*********else if the report->type == ??***********/

            } //ie no session token exists and therefore the user is not authenticated
            else {
                return Redirect::to('/login');
            }
        } //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying edit report page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying edit report form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying edit report';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit report page';
            return view('error-msg')->with('msg', $error);
        }
    }


    /**
     * Show the form for editing the selected case note in the report.
     * @param $reportId
     * @param $caseNoteId
     */

    public function editCaseNote($caseNoteId, $reportId)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->get(Config::get('constants.API_URL') . 'casenote/' . $caseNoteId . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $data = json_decode((string)$response->getBody());

                //format dates to be mm/dd/yyyy for case notes and extract time
                $t = $data->caseNote->created_at;

                $dt = new DateTime($t);
                $noteDate = $dt->format('m/d/Y');

                $employee = $data->firstName[0] . ' ' . $data->lastName[0];

                return view('report/case_notes/edit-case-note')
                    ->with(array(
                        'data' => $data,
                        'noteDate' => $noteDate,
                        'employee' => $employee,
                        'reportId' => $reportId
                    ));


            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::back()
                ->withErrors('Error displaying edit case note page');
        } catch (\ErrorException $error) {
            $e = 'Error displaying edit case note form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying edit case note';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit case note page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Update the case note for the report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id of the case_note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $caseNoteId, $reportId)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                //validate input meet's db constraints
                $this->validate($request, [
                    'title' => 'required|max:255',
                    'desc' => 'max:255'
                ]);

                //get the data from the form
                $title = Input::get('title');
                $desc = Input::get('desc');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'casenote/' . $caseNoteId . '/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json',
                            'X-HTTP-Method-Override' => 'PUT'
                        ),
                        'json' => array('title' => $title, 'desc' => $desc
                        )
                    )
                );

                $casenote = json_decode((string)$response->getBody());

                //direct user based on whether record updated successfully or not
                if ($casenote->success == true) {

                    $theAction = 'You have successfully edited the case note';

                    return view('confirm-id-url-msg')->with(array(
                        'theAction' => $theAction,
                        'reportId' => $reportId
                    ));
                } else {
                    return Redirect::to('/edit-case-notes/' . $caseNoteId . '/reports/' . $reportId)
                        ->withInput()
                        ->withErrors('Unable to update the case note. Please check input is valid.');
                }
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $error) {
            return Redirect::to('/edit-case-notes/' . $caseNoteId . '/reports/' . $reportId)
                ->withInput()
                ->withErrors('Error updating case note. Please fill in all required fields or check input.');

        } catch (\ErrorException $error) {
            //catches for such things as input doesn't feed well into code
            // and update fails due to db integrity constraints
            return Redirect::to('/edit-case-notes/' . $caseNoteId . '/reports/' . $reportId)
                ->withInput()
                ->withErrors('Unable to update the case note. Probably due to invalid input.');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/edit-case-notes/' . $caseNoteId . '/reports/' . $reportId)
                ->withInput()
                ->withErrors('Error updating case note. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        }


    }

    /**
     * Remove the specified resource from storage.
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

                $client->post(Config::get('constants.API_URL') . 'reports/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'
                    ]
                ]);

                $theAction = 'You have successfully deleted the report';

                return view('confirm')->with('theAction', $theAction);
            } //user not authenticated
            else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Operation Failed. Report not deleted.';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting report';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error removing report from database';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error removing report from system';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCaseNote($reportId, $id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'casenote/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'

                    ]
                ]);

                $theAction = 'You have successfully deleted the case note';

                return view('confirm-id-url-msg')->with(array(
                    'theAction' => $theAction,
                    'reportId' => $reportId

                ));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Operation Failed. Case Note was not deleted.';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting case note';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error removing case note from database';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error removing case note from system';
            return view('error-msg')->with('msg', $error);
        }
    }
}

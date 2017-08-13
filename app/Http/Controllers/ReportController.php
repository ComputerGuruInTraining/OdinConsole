<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Redirect;
use Carbon\Carbon;
use Input;
use DateTime;


//GeoCode TZ key =

class ReportController extends Controller
{
    protected $accessToken;
//    protected $client;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //TODO: v1 complete: format date in list and remove time
    //TODO: v2: in order of start date
    public function index()
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->get('http://odinlite.com/public/api/reports/list/'.$compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,//TODO: Access_token saved for global use
                    ]
                ]);

                $reports = json_decode((string)$response->getBody());

                foreach($reports as $i => $item){
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

                return view('report/reports')->with(array(
                    'reports' => $reports,
                    'url' => 'reports'
                ));

            }
            else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return view('admin_template');
        }
        catch (\ErrorException $error) {
            echo $error;
            return Redirect::to('/login');
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

                $response = $client->get('http://odinlite.com/public/api/locations/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $locations = json_decode((string)$response->getBody());

                return view('report/create')->with('locations', $locations);

            }
            else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/reports');
        }
        catch (\ErrorException $error) {
            echo $error;
            return Redirect::to('/reports');
        }

        return view('report/create')->with();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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

                //convert date strings from mm/dd/yyyy to dd-mm-yyyy
                $dateFrom = jobDateTime($dateFromStr, "00:00");
                $dateTo = jobDateTime($dateToStr, "00:00");

                $token = session('token');
                $compId = session('compId');

                //post to api via function which calls a different route based on report type
                if($type == 'Case Notes') {
                    $result = $this->postCaseNote($location, $type, $dateFrom, $dateTo, $token, $compId);
                }else if($type == 'Cases and Checks') {
                    $result = $this->postCasesChecks($location, $type, $dateFrom, $dateTo, $token, $compId);
                }


                if($result->success == true){
                    return view('confirm-create-general')
                        ->with(array(
                            'theMsg' => 'The report has been successfully generated',
                            'btnText' => 'Generate Report',
                            'url' => 'reports'
                        ));
                }else if($result->success == false){
                    //TODO: untested result-> ... == false
                    $msg = 'Failed to generate report';
                    return view('error')->with('error', $msg);
                }

            }else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
           // dd($e);
            $msg = 'Http Error generating report';
            return view('error')->with('error', $msg);
        }
        catch (\ErrorException $error) {
            //dd($error);
            $msg = 'Error exception generating report';
            return view('error')->with('error', $msg);
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

        $response = $client->post('http://odinlite.com/public/api/reports/casenotes', array(
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
        try {
            $client = new GuzzleHttp\Client;

            $response = $client->post('http://odinlite.com/public/api/reports/casesandchecks', array(
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

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
                // dd($e);
                $msg = 'Http Error generating report';
                return view('error')->with('error', $msg);
            }
        catch (\ErrorException $error) {
                //dd($error);
                $msg = 'Error exception generating report';
                return view('error')->with('error', $msg);
        }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->get('http://odinlite.com/public/api/report/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $report = json_decode((string)$response->getBody());

                //format dates to be in the form 3rd January 2107 for report date range
                $sdate = formatDates($report->date_start);

                $edate = formatDates($report->date_end);

                if ($report->type == 'Case Notes') {

                    $cases = $this->getCaseNotes($id, $token, $report);

                    if($cases != 'error'){
                        foreach ($cases->reportCaseNotes as $i => $item) {
                            //change to collection datatype from array for using groupBy fn
                            $caseNotes = collect($cases->reportCaseNotes);
                            $groupCases = $caseNotes->groupBy('case_date');
                        }

                        return view('report/case_notes/show')->with(array('cases' => $cases,
                            'groupCases' => $groupCases,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate
                        ));
                    }else{
                    $err = 'There were no case notes created during the period that the selected report covers.';
                    $errors = collect($err);
                    return Redirect::to('/reports')->with('errors', $errors);
                    }

                }else if($report->type == 'Cases And Checks'){



                }

            }else {
                //ie no session token exists and therefore the user is not authenticated

                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
            $msg = 'Error exception displaying report';
            return view('error')->with('error', $msg);
        }catch (\ErrorException $error) {
            $msg = 'Error exception displaying report';
            return view('error')->with('error', $msg);
        }
    }

    public function getCaseNotes($id, $token)
    {
        try {
            $client = new GuzzleHttp\Client;

            $response = $client->get('http://odinlite.com/public/api/reportcases/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $cases = json_decode((string)$response->getBody());

            if ($cases->success == false) {
                return 'error';

            } else {
                //extract location latitude and longitude to be used to find timezone
                //for this report atm, case note location is presumed to be location of premise
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

                    $time = date('g.i a', $tsUsingResult);

                    $cases->reportCaseNotes[$i]->case_date = $date;
                    $cases->reportCaseNotes[$i]->case_time = $time;
                }
                return $cases;
            }

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
            echo $e;
            return Redirect::to('/reports');
        }catch (\ErrorException $error) {
            $errors = collect($error);
            return Redirect::to('/reports')->with('errors', $errors);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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

                $response = $client->get('http://odinlite.com/public/api/report/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $report = json_decode((string)$response->getBody());

                if ($report->type == 'Case Notes') {

                    $response = $client->get('http://odinlite.com/public/api/reportcases/' . $id, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token,
                        ]
                    ]);

                    $cases = json_decode((string)$response->getBody());

                    if($cases->success == false) {
                        $err = 'There were no case notes created during the period that the selected report covers.';
                        $errors = collect($err);
                        return Redirect::to('/reports')->with('errors', $errors);
                    }
                    else {

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
                        foreach($cases->reportCaseNotes as $i => $item){
                            //add the extracted date to each of the objects and format date
                            $t = $cases->reportCaseNotes[$i]->created_at;

                            $dt = new DateTime($t);
                            $date = $dt->format('m/d/Y');
                            $time = $dt->format('g.i a');

                            $cases->reportCaseNotes[$i]->case_date = $date;
                            $cases->reportCaseNotes[$i]->case_time = $time;

                            //change to collection datatype from array for using groupBy fn
                            $caseNotes = collect($cases->reportCaseNotes);
                            $groupCases = $caseNotes->groupBy('case_date');

                        }

                        return view('report/case_notes/edit')->with(array('cases' => $cases,
                            'groupCases' => $groupCases,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate,
                            'url' => 'case-notes'
                        ));


                    }
                }

                /*********else if the report->type == ??***********/

            } //ie no session token exists and therefore the user is not authenticated
            else {
                return Redirect::to('/login');
            }
        }
            //get request resulted in an error ie no report_case_id for the report_id ie no shifts during the period at the location
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/reports');
        }
        catch (\ErrorException $error) {
            $errors = collect($error);
            return Redirect::to('/reports')->with('errors', $errors);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, $id)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (session()->has('token')) {

                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $client->delete('http://odinlite.com/public/api/reports/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

//                $responseMsg = json_decode((string)$response->getBody());

                $theAction = 'You have successfully deleted the report';

                return view('confirm')->with('theAction', $theAction);
            }
            //user not authenticated
            else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/reports');
        }
    }
}

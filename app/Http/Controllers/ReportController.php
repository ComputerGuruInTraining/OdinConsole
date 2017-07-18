<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Redirect;
use Carbon\Carbon;
use Input;
use DateTime;


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
                $client = new GuzzleHttp\Client;

                $response = $client->post('http://odinlite.com/public/api/reports', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array(
                            'location' => $location, 'type' => $type,
                            'dateFrom' => $dateFrom, 'dateTo' => $dateTo,
                            'compId' => $compId, 'dateFromOnly' => $dateFromStr,
                            'dateToOnly' => $dateToStr
                        )
                    )
                );

                $success = GuzzleHttp\json_decode((string)$response->getBody());

                return view('confirm-create-general')
                    ->with(array(
                    'theMsg' => 'The report has been successfully generated',
                    'btnText' => 'Generate Report',
                    'url' => 'reports'
                ));

            }
            else {
                return Redirect::to('/reports');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            //rather than displaying an error page, redirect users to dashboard/login page (preferable)
            return Redirect::to('/reports');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //TODO: HIGH v1 working/complete catch for null values on show blade
    //TODO: HIGH v1 working/complete format view properly. Messy atm
    //TODO: now gather report info too for the entire report
    //TODO: now select other details such as total_hours etc from report_cases
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
                        $sdate = $sdt->format('m/d/Y');

                        $e = $report->date_end;

                        $edt = new DateTime($e);
                        $edate = $edt->format('m/d/Y');

                       // $report->put('start', $sdate);
                      //  $report->put('end', $edate);
                        //  }

                        //format dates to be mm/dd/yyyy for case notes
                        foreach($cases->reportCaseNotes as $i => $item){
                            //add the extracted date to each of the objects and format date
                            $s = $cases->reportCaseNotes[$i]->created_at;

                            $sdt = new DateTime($s);
                            $date = $sdt->format('m/d/Y');

                            $cases->reportCaseNotes[$i]->case_date = $date;
                        }


                        return view('report/case_notes/show')->with(array('cases' => $cases,
                            'report' => $report,
                            'start' => $sdate,
                            'end' => $edate
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
//    {
//        //
//    }

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

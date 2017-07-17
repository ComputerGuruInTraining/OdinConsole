<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Redirect;
use Carbon\Carbon;
use Input;


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

                return view('report/reports')->with('reports', $reports);

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


//    public function oauth(){
//        $client = new GuzzleHttp\Client;
//
//        try {
//            $response = $client->post('http://odinlite.com/public/oauth/token', [
//                'form_params' => [
//                    'client_id' => 2,
//                    // The secret generated when you ran: php artisan passport:install
//                    'client_secret' => 'OLniZWzuDJ8GSEVacBlzQgS0SHvzAZf1pA1cfShZ',
//                    'grant_type' => 'password',
//                    'username' => 'bernadettecar77@hotmail.com',
//                    'password' => 'password',
//                    'scope' => '*',
//                ]
//            ]);
//
//            $auth = json_decode((string)$response->getBody());
//
//            //TODO: You'd typically save this payload in the session
//            $this->accessToken = $auth->access_token;
//
//        } catch (GuzzleHttp\Exception\BadResponseException $e) {
//            echo 'oauth fn error';
//        }
//    }
//
//    public function accessToken(){
//       return $this->accessToken;
//    }
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

//                dd($location, $type, $dateFrom, $dateTo);

                //process start date and time before adding to db
                //function in functions.php
//                $strStart = jobDateTime($dateStart, $timeStart);
//                $strEnd = jobDateTime($dateEnd, $timeEnd);

//        $this->oauth();

                //retrieve token needed for authorized http requests
//        $token = $this->accessToken();

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

               // dd($success);
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
    public function show($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->get('http://odinlite.com/public/api/reports/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $reportCaseNotes = json_decode((string)$response->getBody());

//            foreach($cases as $case){
//                dd($case->id);
//
//            }

                //need to retrieve the case notes for the

                return view('report/case_notes/show')->with('cases', $reportCaseNotes);
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
            return Redirect::to('/login');
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

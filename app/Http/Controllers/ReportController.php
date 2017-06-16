<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use GuzzleHttp;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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


            // You'd typically save this payload in the session
            $auth = json_decode((string)$response->getBody());

            $response = $client->get('http://odinlite.com/public/api/reportcases/list', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $auth->access_token,//TODO: Access_token saved for global use
                ]
            ]);

            $reports = json_decode((string)$response->getBody());

//TODO: pass through case notes and report data
            return view('report/case_notes/reports')->with('reports', $reports);
//    dd($auth->access_token);

//
//
//    $request = new Request('GET', 'http://localhost:8000/api/reports/all',
//        [
//
//        'Authorization' => 'Bearer '.$auth->access_token
//        ]);
//
////    dd($request)$response = $client->request('GET', '/get', ('http://localhost:8000/api/report-case-notes/list', [
//        'headers' => [
//            'Content-Type', 'application/x-www-form-urlencoded',
//            'Accept' => 'application/json',
//            'Authorization' => 'Bearer '.$auth->access_token,
//        ]
//    ]);;
//    //error in following code:
//    $clientGet->send($request);
//    dd($request);

//
//    dd($auth->access_token);
//    dd($request, $response);
//    $reports = json_decode( (string) $response->getBody() );
////    dd($report);
//    $reportList = "";
//    foreach ($reports as $report) {
//        $reportList .= "<li>{$report->location_id}</li>";
//    }
//
//    echo "<ul>{$reportList}</ul>";

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('report/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

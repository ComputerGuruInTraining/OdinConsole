<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use GuzzleHttp;


class ReportController extends Controller
{
    protected $accessToken;
//    protected $client;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO: this fn needs to be called at login
        $this->oauth();

        //retrieve token needed for authorized http requests
        $token = $this->accessToken();

        $client = new GuzzleHttp\Client;

        $response = $client->get('http://odinlite.com/public/api/reports/list', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,//TODO: Access_token saved for global use
            ]
        ]);

        $reports = json_decode((string)$response->getBody());

        return view('report/reports')->with('reports', $reports);

//    $reportList = "";
//    foreach ($reports as $report) {
//        $reportList .= "<li>{$report->location_id}</li>";
//    }
//
//    echo "<ul>{$reportList}</ul>";

    }

    //TODO: improve so pass in username and password
    //TODO: move fn to a utility file or authservice file
    public function oauth(){
        $client = new GuzzleHttp\Client;

        try {
            $response = $client->post('http://odinlite.com/public/oauth/token', [
                'form_params' => [
                    'client_id' => 2,
                    // The secret generated when you ran: php artisan passport:install
                    'client_secret' => 'OLniZWzuDJ8GSEVacBlzQgS0SHvzAZf1pA1cfShZ',
                    'grant_type' => 'password',
                    'username' => 'bernadettecar77@hotmail.com',
                    'password' => 'password',
                    'scope' => '*',
                ]
            ]);

            $auth = json_decode((string)$response->getBody());

            //TODO: You'd typically save this payload in the session
            $this->accessToken = $auth->access_token;

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo 'oauth fn error';
        }
    }

    //TODO: move fn to a utility file or authservice file
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
        $locations = Location::all('id','name');
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
        try {

            $this->oauth();

            $token = $this->accessToken();

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
        catch (GuzzleHttp\Exception\BadResponseException $e){
            $error = "show fn error";
            echo $error;
//            TODO: go back to previous view or redirect without having to pass through values again

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

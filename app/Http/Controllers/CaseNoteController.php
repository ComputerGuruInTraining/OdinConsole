<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Input;
use DateTime;
use Redirect;


class CaseNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Redirect::to('/reports');
    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->get('http://odinlite.com/public/api/casenote/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $data = json_decode((string)$response->getBody());

                //format dates to be mm/dd/yyyy for case notes and extract time
                $t = $data->caseNote->created_at;

                $dt = new DateTime($t);
                $noteDate = $dt->format('m/d/Y');
                $time = $dt->format('g.i a');

                $employee = $data->firstName[0].' '.$data->lastName[0];

                return view('case_note_actions/edit-case-note')
                    ->with(array(
                        'data' => $data,
                        'noteDate' => $noteDate,
                        'time' => $time,
                        'employee' => $employee
                    ));


            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            $err = 'Error';
            $errors = collect($err);
            return Redirect::back()
                ->withErrors('Error getting case note');
        }
        catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
            $e = 'Error';
            $errors = collect($e);
            return view('location/create-locations')->with('errors', $errors);
        }
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
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                //validate input meet's db constraints
                $this->validate($request, [
                    'title' => 'required|max:255',
                    'desc' => 'required|max:255'
                ]);

                //get the data from the form
                $title = Input::get('title');
                $desc = Input::get('desc');

                $client = new GuzzleHttp\Client;

                $response = $client->put('http://odinlite.com/public/api/casenote/'.$id.'/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('title' => $title, 'desc' => $desc
                        )
                    )
                );

                $casenote = json_decode((string)$response->getBody());

                //direct user based on whether record updated successfully or not
                if($casenote->success == true)
                {
                    $theAction = 'You have successfully edited the case note details';

                    return view('confirm')->with(array('theAction' => $theAction));
                }
                else{
                    return redirect()->route("case-notes.edit");
                }
            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $error) {
            $e = 'Please fill in all required fields or check input';
            $errors = collect($e);
            return view('report/case_notes/edit')->with('errors', $errors);
        }
    }

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

                $response = $client->delete('http://odinlite.com/public/api/casenote/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $theAction = 'You have successfully deleted the case note';

                return view('confirm')->with('theAction', $theAction);
            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/reports');
        }

    }
}

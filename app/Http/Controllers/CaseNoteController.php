<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Input;
use DateTime;
use Redirect;
use Config;

class CaseNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->get(Config::get('constants.API_URL') . '/casenotes/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $data = json_decode((string)$response->getBody());

                $dataFormat = $this->formatCaseNotes($data);

                $cases = collect($dataFormat);//must collect or error = undefined method stdClass::groupBy(

                $groupedData = $cases->groupBy('location');
////
//                $casesGroup = $groupedData->groupBy('case_id');


//                $groupedData = $cases->groupBy('location_id')->toArray() +
//                    $cases->groupBy('case_id')->toArray();
//                dd($cases);
//                dd($cases);
//                dd($groupedData,
//                    $dataFormat->locations);
//                dd(gettype($data->cases), gettype($data->locations));
//                dd($dataFormat);

//                foreach ($reports as $i => $item) {
//                    //add the extracted date to each of the objects and format date
//                    $s = $item->date_start;
//
//                    $sdt = new DateTime($s);
//                    $sdate = $sdt->format('m/d/Y');
//
//                    $e = $item->date_end;
//
//                    $edt = new DateTime($e);
//                    $edate = $edt->format('m/d/Y');
//
//                    $reports[$i]->form_start = $sdate;
//                    $reports[$i]->form_end = $edate;
//                }

                return view('case_note_actions/index')->with(array(
                    'cases' => $groupedData,
//                    'locations' => $dataFormat->locations,
                    'url' => 'case-notes'
                ));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying case notes';
            return view('error')->with('error', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying case notes page';
            return view('error')->with('error', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display case notes';
            return view('error')->with('error', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading case notes';
            return view('error')->with('error', $error);
        }
    }

    /*
     * Format: date, time and add to each object
     * */
    public function formatCaseNotes($data){
      //  ensure there are $locations before adding the location names onto the end of case object


        //per case_note, if the location_id is equal to the location object id
        //use the location object latitude and longitude
        //and the case_note created_at timestamp
        //to convert to a timezone date and time
//        if(sizeof($data->locations) > 0) {
            foreach ($data as $i => $case) {
//                dd($case, $i, $data[$i]->location_id);

//        foreach()
//                foreach ($data->locations as $location){
//                    if ($location->id == $data->cases[$i]->location_id) {

                        //extract location latitude and longitude to be used to find timezone
                        //for this atm, case note geoLocation is presumed to be location of premise
//                        $lat = $location->latitude;
//                        $long = $location->longitude;
                        //calculate the date and time based on the location and any of the case notes created_at timestamp
                        $collection = timezone($data[$i]->locLat, $data[$i]->locLong, $case->created_at);
//
//                        //format dates to be mm/dd/yyyy for case notes
//                            //add the extracted date to each of the objects and format date to be mm/dd/yyyy
                            $t = $case->created_at;
//
//                            //friendly dates
                            $dateForTS = date_create($t);
                            $dateInTS = date_timestamp_get($dateForTS);
//
//                            //google timezone api returns the time in seconds from utc time (rawOffset)
//                            //and a value for if in daylight savings timezone (dstOffset) which will equal 0 if not applicable
                            $tsUsingResult = $dateInTS + $collection->get('dstOffset') + $collection->get('rawOffset');
//
//                            //convert timestamp to a datetime string
                            $date = date('m/d/Y', $tsUsingResult);
//
                            $time = date('g.i a', $tsUsingResult);
//
                        $data[$i]->date = $date;
                        $data[$i]->time = $time;


                        //convert to substring the filepath to be used to download the file, if there is an image
                        $stringImg = $case->img;

                        if($stringImg != ""){
//                            $substrImg = substrImg($stringImg);
//                            $data[$i]->img = $substrImg;
                              $data[$i]->img = "Y";

//                            dd($data[$i]->img, $substrImg);
                        }



//                        $cases[$i]->location = $location->name;
//                    }
//                }
            }
//        }
            return $data;
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

                $response = $client->get(Config::get('constants.API_URL').'casenote/' . $id . '/edit', [
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
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::back()
                ->withErrors('Error displaying edit case note page');
        } catch (\ErrorException $error) {
            $e = 'Error displaying edit case note form';
            return view('error')->with('error', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying edit case note';
            return view('error')->with('error', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit case note page';
            return view('error')->with('error', $error);
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
                    'desc' => 'max:255'
                ]);

                //get the data from the form
                $title = Input::get('title');
                $desc = Input::get('desc');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL').'casenote/'.$id.'/edit', array(
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
                if($casenote->success == true)
                {
                    $theAction = 'You have successfully edited the case note details';

                    return view('confirm')->with(array(
                        'theAction' => $theAction
                       ));
                }
                else{
                    return redirect()->route("case-notes.edit");
                }
            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $error) {
            return Redirect::to('/case-notes/'.$id.'/edit')
                ->withInput()
                ->withErrors('Error updating case note. Please fill in all required fields or check input.');

        } catch (\ErrorException $error) {
            //catches for such things as input doesn't feed well into code
            // and update fails due to db integrity constraints
                return Redirect::to('/case-notes/'.$id.'/edit')
                    ->withInput()
                    ->withErrors('Unable to update the case note. Probably due to invalid input.');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/case-notes/'.$id.'/edit')
                ->withInput()
                ->withErrors('Error updating case note. Please check input is valid.');

        }
//catch(\Exception $exception) {
//            //one instance is when no address input
//            return Redirect::to('/case-notes/'.$id.'/edit')
//                ->withInput()
//                ->withErrors('Operation failed. Please ensure input valid.');
//
//        }
        catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
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

                $response = $client->post(Config::get('constants.API_URL').'casenote/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'

                    ]
                ]);

                $theAction = 'You have successfully deleted the case note';

                return view('confirm')->with('theAction', $theAction);
            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Operation Failed';
            return view('error')->with('error', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting case note';
            return view('error')->with('error', $e);

        } catch (\Exception $err) {
            $e = 'Error removing case note from database';
            return view('error')->with('error', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error removing case note from system';
            return view('error')->with('error', $error);
        }
    }

    function download($img)
    {
        //http request
        if (session()->has('token')) {
            //retrieve token needed for authorized http requests
//            $token = session('token');

            $client = new GuzzleHttp\Client;

//            $compId = session('compId');

            $response = $client->get(Config::get('constants.STANDARD_URL') . '/download-photo/'.$img, [
//                'headers' => array(
//                    'Content-Type' => 'application/json'
//                ),
            ]);

            $data = json_decode((string)$response->getBody());

            //a file is returned from inthe response which forces the user's browser to download the photo

            dd($data);
        }

    }
}

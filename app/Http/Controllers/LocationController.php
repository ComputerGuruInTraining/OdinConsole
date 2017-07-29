<?php

namespace App\Http\Controllers;

use App\Utlities\ApiAuth;
use Illuminate\Http\Request;
use Input;
use Form;
use Model;
use App\Models\Location;
use GuzzleHttp;
use Psy\Exception\ErrorException;
use Redirect;

class LocationController extends Controller
{
//    public $accessToken;

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

                $response = $client->get('http://odinlite.com/public/api/locations/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $locations = json_decode((string)$response->getBody());

                return view('location/locations')->with(array('locations' => $locations, 'url' => 'locations'));

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
        if (session()->has('token')) {
            return view('location/create-locations');
        }
        else {
            return Redirect::to('/login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //TODO: improve validation for adding location (see update-location)
    //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field
    public function store(Request $request)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

//            validate data
                $this->validate($request, [
                    'name' => 'required|max:255',
                    'address' => 'required|max:255',
                ]);

//            //gather data from input fields
                $name = ucfirst(Input::get('name'));
                $address = Input::get('address');
                $geoCoords = $this->geoCode($address);
                $latitude = $geoCoords->results[0]->geometry->location->lat;
                $longitude = $geoCoords->results[0]->geometry->location->lng;
                $notes = ucfirst(Input::get('info'));

                $response = $client->post('http://odinlite.com/public/api/locations', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('name' => $name, 'address' => $address,
                            'latitude' => $latitude, 'longitude' => $longitude,
                            'notes' => $notes, 'compId' => $compId
                        )
                    )
                );

                //display confirmation page
                return view('confirm-create')->with(array('theData' => $name, 'url' => 'locations', 'entity' => 'Location'));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            $err = 'Please provide a valid address and ensure the address is not already stored in the database.';
            $errors = collect($err);
            return view('location/create-locations')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
                $e = 'Please fill in all required fields';
                $errors = collect($e);
                return view('location/create-locations')->with('errors', $errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        $this->oauth();
//
//        //retrieve token needed for authorized http requests
//        $token = $this->accessToken();
//
//        $client = new GuzzleHttp\Client;
//
//        $response = $client->get('http://odinlite.com/public/api/location/'.$id, [
//            'headers' => [
//                'Authorization' => 'Bearer ' . $token,
//            ]
//        ]);
//
//        $location = json_decode((string)$response->getBody());
//
//        return view('location/show')->with('location', $location);
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

                $response = $client->get('http://odinlite.com/public/api/locations/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $location = json_decode((string)$response->getBody());

                return view('location/edit-locations')->with('location', $location);

            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            $err = 'Error';
            $errors = collect($err);
            return view('location/create-locations')->with('errors', $errors);
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

                $address = Input::get('address');

                //api put route will make no changes when these values are found in put request
                if ($address == "") {
                    $address = '';
                    $latitude = 0.0;
                    $longitude = 0.0;
                }
                else{
                    //get the geoCoords for the address
                    $geoCoords = $this->geoCode($address);
                    $latitude = $geoCoords->results[0]->geometry->location->lat;
                    $longitude = $geoCoords->results[0]->geometry->location->lng;
                }

                //validate data
                $this->validate($request, [
                    'name' => 'required|max:255',
                    'address' => 'max:255',//not required for edit page, will presume same if not input
                ]);

                //get the data from the form
                $name = ucfirst(Input::get('name'));
                $notes = ucfirst(Input::get('notes'));

                $client = new GuzzleHttp\Client;

                $response = $client->put('http://odinlite.com/public/api/locations/'.$id.'/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('name' => $name, 'address' => $address,
                            'latitude' => $latitude, 'longitude' => $longitude,
                            'notes' => $notes
                        )
                    )
                );

                $location = json_decode((string)$response->getBody());

                //direct user based on whether record updated successfully or not
                if($location->success == true)
                {
                    $theAction = 'You have successfully edited the location';

                    return view('confirm')->with(array('theAction' => $theAction));
                }
                else{
                    return redirect()->route("location.edit_locations");
                }
            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Please provide valid changes';
            $errors = collect($err);
            echo($err);
            return Redirect::to('/locations');
        }
        catch (\ErrorException $error) {
            //catches for such things as address not able to be converted to geocoords and update fails due to db integrity constraints
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
                $errors = collect($e);
                echo($error);
                return Redirect::to('/locations');
//fixme: proper validation: ->with('errors', $errors)
            } else {
                echo($error);
                return Redirect::to('/locations');
            }
        }
    }

    /**Fn implemented on confirm-delete.blade.php page
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

                $response = $client->delete('http://odinlite.com/public/api/locations/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $theAction = 'You have successfully deleted the location';

                return view('confirm')->with('theAction', $theAction);
            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/locations');
        }
    }

    public function geoCode($address)
    {
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&key=AIzaSyA1HtcSijw1F0mJRLpsr8ST5koG4T9_tew');
        $output = json_decode($geocode);
        return $output;
    }
}

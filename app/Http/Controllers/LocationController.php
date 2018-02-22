<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Form;
use Model;
use GuzzleHttp;
use Psy\Exception\ErrorException;
use Redirect;
use Hash;
use Config;

class LocationController extends Controller
{

//    protected $name = "";
//    protected $address = "";
//    protected $latitude = 0;
//    protected $longitude = 0;
//    protected $notes = "";
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

                $response = $client->get(Config::get('constants.API_URL') . 'locations/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $locations = json_decode((string)$response->getBody());

                $locations = array_sort($locations, 'name', SORT_ASC);

                return view('location/locations')->with(array(
                    'locations' => $locations,
                    'url' => 'location'));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying locations';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying location page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display locations';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading locations';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*with company verification*/
    public function show($id)
    {
        try {
            if (session()->has('token')) {

                $location = $this->getLocation($id);

                //ie record and user belong to different companies, therefore user not verified
                if ($location == false) {

                    return verificationFailedMsg();

                }

                //the $show variable is used on the map to determine whether to include input fields or not
                return view('location/show')->with(array(
                    'location' => $location,
                    'show' => 'show',
                    'url' => 'location'
                    ));

            } else {
                return Redirect::to('/login');
            }


        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying locations';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying location page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display locations';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading locations';
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
                return view('location/create-locations');
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying add location page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying add location form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying add location';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading add location page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field
    public function store()
    {
        try {

            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $alias = session('alias');
                $address = session('address');
                $latitude = session('latitude');
                $longitude = session('longitude');
                $notes = session('notes');

                $response = $client->post(Config::get('constants.API_URL') . 'locations', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('name' => $alias, 'address' => $address,
                            'latitude' => $latitude, 'longitude' => $longitude,
                            'notes' => $notes, 'compId' => $compId
                        )
                    )
                );

                $reply = json_decode((string)$response->getBody());

                if ($reply->success == true) {
                    //display confirmation page
                    return view('confirm-create-manual')->with(array(
                        'theData' => $address,
                        'url' => 'location-create',
                        'entity' => 'Location')
                    );
                } else {
                    return Redirect::to('/location-create');
                }

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Unable to store the location. Probably due to an invalid address 
                or the address is already stored in the database.';
            $errors = collect($err);
            return view('location/create-locations')->with('errors', $errors);

        } catch (\ErrorException $error) {
            //catches for such things as address not able to be converted to geocoords
            // and update fails due to db integrity constraints
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
                $errors = collect($e);
                return view('location/create-locations')->with('errors', $errors);
            } else {
                return Redirect::to('/location-create')
                    ->withInput()
                    ->withErrors('Unable to store the location. Probably due to invalid input.');
            }

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/location-create')
                ->withInput()
                ->withErrors('Error storing location. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');
                    }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*with company verification*/
    public function edit($id)
    {
        try {
            if (session()->has('token')) {

                $location = $this->getLocation($id);

                //ie record and user belong to different companies, therefore user not verified
                if ($location == false) {

                    return verificationFailedMsg();

                }

                return view('location/edit-locations')->with('location', $location);

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying edit location page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying edit location form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying edit location';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit location page';
            return view('error-msg')->with('msg', $error);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $aliasEdit = session('aliasEdit');
                $addressEdit = session('addressEdit');
                $latitudeEdit = session('latitudeEdit');
                $longitudeEdit = session('longitudeEdit');
                $notesEdit = session('notesEdit');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL') . 'locations/' . $id . '/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json',
                            'X-HTTP-Method-Override' => 'PUT'
                        ),
                        'json' => array('name' => $aliasEdit, 'address' => $addressEdit,
                            'latitude' => $latitudeEdit, 'longitude' => $longitudeEdit,
                            'notes' => $notesEdit
                        )
                    )
                );

                $location = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($location == false) {

                    return verificationFailedMsg();
                }

                //direct user based on whether record updated successfully or not
                if ($location->success == true) {
                    $theAction = 'You have successfully edited the location';

                    return view('confirm')->with(array('theAction' => $theAction));
                } else {
                    return redirect()->route("location.edit_locations");
                }
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('location-edit-' . $id)
                ->withInput()
                ->withErrors('Unable to update the location. Probably due to an invalid address 
                or the address is already stored in the database.');

        } catch (\ErrorException $error) {
            //catches for such things as address not able to be converted to geocoords
            // and update fails due to db integrity constraints
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
                $errors = collect($e);
                return view('location/edit-locations')->with('errors', $errors);
            } else {
                return Redirect::to('/location-edit-' . $id)
                    ->withInput()
                    ->withErrors('Unable to update the location. Probably due to invalid input.');
            }

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/location-edit-' . $id)
                ->withInput()
                ->withErrors('Error updating location. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('/');
        }
    }

    /**Fn implemented on confirm-delete.blade.php page
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

                $response = $client->post(Config::get('constants.API_URL') . 'locations/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'
                    ]
                ]);

                $location = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($location == false) {

                    return verificationFailedMsg();
                }

                $theAction = 'You have successfully deleted the location';

                return view('confirm')->with('theAction', $theAction);
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Operation Failed';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting location';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error removing location from database';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');
                    } catch (\InvalidArgumentException $invalid) {
            $error = 'Error removing location from system';
            return view('error-msg')->with('msg', $error);
        }
    }

    public function geoCode($address)
    {
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&key=AIzaSyA1HtcSijw1F0mJRLpsr8ST5koG4T9_tew');
        $output = json_decode($geocode);
        return $output;
    }

    public function confirmCreate(Request $request)
    {
        try {
            if (session()->has('token')) {

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

                //flush old values from session that were present due to previous creates
                session()->forget('alias');
                session()->forget('address');
                session()->forget('latitude');
                session()->forget('longitude');
                session()->forget('notes');

                //save in session for use by store()
                session([
                    'alias' => $name,
                    'address' => $address,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'notes' => $notes
                    ]);

                return view('location/confirm-create-locations')->with(array(
                    'alias' => $name,
                    'address' => $address,
                    'notes' => $notes,
                    'lat' => $latitude,
                    'long' => $longitude

                ));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Unable to store the location. Probably due to an invalid address 
                or the address is already stored in the database.';
            return Redirect::to('/location-create')
                ->withInput()
                ->withErrors($err);

        } catch (\ErrorException $error) {
            //catches for such things as address not able to be converted to geocoords
            // and update fails due to db integrity constraints
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
//                $errors = collect($e);
                return Redirect::to('/location-create')
                    ->withInput()
                    ->withErrors($e);
            } else {
                return Redirect::to('/location-create')
                    ->withInput()
                    ->withErrors('Unable to store the location. Probably due to invalid input.');
            }

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/location-create')
                ->withInput()
                ->withErrors('Error storing location. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');
                    }
    }

    public function confirmEdit(Request $request, $id)
    {
        try {
            if (session()->has('token')) {

                //validate data
                $this->validate($request, [
                    'name' => 'required|max:255',
                    'address' => 'max:255',//not required for edit page, will presume same if not input
                ]);

                //get the data from the form
                $name = ucfirst(Input::get('name'));
                $notes = ucfirst(Input::get('notes'));
                $address = Input::get('address');

                //api put route will make no changes to address and geoCoords when these values are found in put request
                if ($address == "") {
                    //values for update
                    $address = '';
                    $latitude = 0.0;
                    $longitude = 0.0;

                    //values for map confirm page, pushed to view
                    $location = $this->getLocation($id);

                    //ie record and user belong to different companies, therefore user not verified
                    if ($location == false) {

                        return verificationFailedMsg();

                    }

                    $sameAddress = $location->address;
                    $sameLatitude = $location->latitude;
                    $sameLongitude = $location->longitude;

                    //flush old values from session that were present due to previous edits
                    session()->forget('aliasEdit');
                    session()->forget('addressEdit');
                    session()->forget('latitudeEdit');
                    session()->forget('longitudeEdit');
                    session()->forget('notesEdit');

                    //save in session for use by update().
                    session([
                        'aliasEdit' => $name,
                        'addressEdit' => $address,
                        'latitudeEdit' => $latitude,
                        'longitudeEdit' => $longitude,
                        'notesEdit' => $notes
                    ]);

                    return view('location/confirm-edit-locations')->with(array(
                        'alias' => $name,
                        'address' => $sameAddress,
                        'notes' => $notes,
                        'lat' => $sameLatitude,
                        'long' => $sameLongitude,
                        'id' => $id
                    ));

                } else {
                    //get the geoCoords for the address
                    $geoCoords = $this->geoCode($address);
                    $latitude = $geoCoords->results[0]->geometry->location->lat;
                    $longitude = $geoCoords->results[0]->geometry->location->lng;

                    //save in session for use by update().
                    session([
                        'aliasEdit' => $name,
                        'addressEdit' => $address,
                        'latitudeEdit' => $latitude,
                        'longitudeEdit' => $longitude,
                        'notesEdit' => $notes
                    ]);

                    return view('location/confirm-edit-locations')->with(array(
                        'alias' => $name,
                        'address' => $address,
                        'notes' => $notes,
                        'lat' => $latitude,
                        'long' => $longitude,
                        'id' => $id
                    ));
                }

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('location-edit-' . $id)
                ->withInput()
                ->withErrors('Unable to update the location. Probably due to an invalid address 
                or the address is already stored in the database.');

        } catch (\ErrorException $error) {
            //catches for such things as address not able to be converted to geocoords
            // and update fails due to db integrity constraints
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
                $errors = collect($e);
                return view('location/edit-locations')->with('errors', $errors);
            } else {
                return Redirect::to('/location-edit-' . $id)
                    ->withInput()
                    ->withErrors('Unable to update the location. Probably due to invalid input.');
            }

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/location-edit-' . $id)
                ->withInput()
                ->withErrors('Error updating location. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');
        }
    }

    public function confirmCreateCancel()
    {
        try {
            if (session()->has('token')) {

//                get the values from the session
                $address = session('address');
                $alias = session('alias');
                $notes = session('notes');

                //pass to the view
                return view('location/create-locations')->with(array(
                    'addressConfirm' => $address,
                    'aliasConfirm' => $alias,
                    'notesConfirm'=> $notes
                ));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying add location page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying add location form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying add location';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading add location page';
            return view('error-msg')->with('msg', $error);
        }
    }

    function confirmEditCancel($id){
        try {
            if (session()->has('token')) {

                //values for map confirm page, pushed to view
                $location = $this->getLocation($id);

                //ie record and user belong to different companies, therefore user not verified
                if ($location == false) {

                    return verificationFailedMsg();

                }
                //any edited details
                $address = session('addressEdit');
                $name = session('aliasEdit');
                $notes = session('notesEdit');
                $latitude = session('latitudeEdit');
                $longitude = session('longitudeEdit');

                return view('location/edit-locations')->with(array(
                    'location' => $location,
                    'addressEdit' => $address,
                    'aliasEdit' => $name,
                    'notesEdit' => $notes,
                    'latitudeEdit' => $latitude,
                    'longitudeEdit' => $longitude
                ));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying edit location page';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying edit location form';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying edit location';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit location page';
            return view('error-msg')->with('msg', $error);
        }

    }

    function getLocation($id){

        $token = session('token');

        $client = new GuzzleHttp\Client;

        $response = $client->get(Config::get('constants.API_URL') . 'locations/' . $id . '/edit', [
        'headers' => [
        'Authorization' => 'Bearer ' . $token,
        ]
        ]);

        $location = json_decode((string)$response->getBody());

        return $location;

    }


}

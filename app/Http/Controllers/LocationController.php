<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Form;
use Model;
use App\Models\Location;
use GuzzleHttp;
use Redirect;

class LocationController extends Controller
{
//TODO: add to location_companies at the same time as adding location
    protected $accessToken;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        try {
        $this->oauth();

        //retrieve token needed for authorized http requests
        $token = $this->accessToken();

        $client = new GuzzleHttp\Client;

        $response = $client->get('http://odinlite.com/public/api/locations/list', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,//TODO: Access_token saved for global use
            ]
        ]);

        $locations = json_decode((string)$response->getBody());

        return view('location/locations')->with(array('locations' => $locations, 'url' => 'locations'));

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return view('admin_template');
        }
    }

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
            echo $e;
            return view('admin_template');
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
        return view('location/create-locations');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //FIXME: TS POST
    //TODO: improve validation for adding location (see update-location)
    //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field
    public function store(Request $request)
    {
        try {
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

            //todo: get the consoleUserId by the current user
            $consoleUserId = 2;

            //post request to api
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->post('http://odinlite.com/public/api/locations', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array('name' => $name, 'address' => $address,
                    'latitude' => $latitude, 'longitude' => $longitude,
                        'notes' => $notes, 'consoleUserId' => $consoleUserId
                    )
                )
            );

            //$apiResponse = json_decode((string)$response->getBody());

            //display confirmation page
            return view('confirm-create')->with(array('theData' => $name, 'url' => 'locations', 'entity' => 'Location'));

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

    //before api request, just retrieving from db
//    public function store(Request $request)
//    {
//        try {
//            //validate data
//            $this->validate($request, [
//                'name' => 'required|unique:locations|max:255',
//                'address' => 'required|unique:locations|max:255',
//            ]);
//
//
//            //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field
//            // to type address and select from dropdown
//            //store the data in the db
//            $location = new Location;
//            $location->name = ucfirst(Input::get('name'));
//            $address = Input::get('address');
//            $location->address = $address;
//            $geoCoords = $this->geoCode($address);
//            $location->latitude = $geoCoords->results[0]->geometry->location->lat;
//            $location->longitude = $geoCoords->results[0]->geometry->location->lng;
//
//            $location->additional_info = ucfirst(Input::get('info'));
//            $location->save();
//
//            //display confirmation page
//            $theData = "You have successfully added the location $location->name";
//            return view('confirm-create')->with(array('theData' => $theData, 'url' => 'locations'));
//
//        } catch (\ErrorException $error) {
//            if ($error->getMessage() == 'Undefined offset: 0') {
//                $e = 'Please provide a valid address';
//                $errors = collect($e);
//                return view('location/create-locations')->with('errors', $errors);
//            } else {
//                return view("location/create-locations");
//            }
//        }
//    }

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
        $this->oauth();

        //retrieve token needed for authorized http requests
        $token = $this->accessToken();

        $client = new GuzzleHttp\Client;

        $response = $client->get('http://odinlite.com/public/api/locations/'.$id.'/edit', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);

        $location = json_decode((string)$response->getBody());

        return view('location/edit-locations')->with('location', $location);
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

            //request to api
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;

            $response = $client->delete('http://odinlite.com/public/api/locations/'.$id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,//TODO: Access_token saved for global use
                ]
            ]);

            $responseMsg = json_decode((string)$response->getBody());

            $theAction = 'You have successfully deleted the location';

            return view('confirm')->with('theAction', $theAction);

        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/locations');
        }
    }

    public static function confirmDelete($id)
    {
        try{

            $client = new GuzzleHttp\Client;

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

            $auth = json_decode((string)$response->getBody());

            //TODO: You'd typically save this payload in the session
            $tokenStatic = $auth->access_token;


            $response = $client->get('http://odinlite.com/public/api/location/'.$id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenStatic,//TODO: Access_token saved for global use
                ]
            ]);

            $location = json_decode((string)$response->getBody());

            $name = $location->name;
            $id = $location->id;
            $url = 'locations';
            return view('confirm-delete')->with(array('fieldDesc' => $name, 'id' => $id, 'url' => $url));
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            Redirect::to('/locations');
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

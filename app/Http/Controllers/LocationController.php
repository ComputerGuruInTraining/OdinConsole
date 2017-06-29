<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Form;
use Model;
use App\Models\Location;
use GuzzleHttp;

class LocationController extends Controller
{

    protected $accessToken;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //retrieve all records from locations table via Location Model
//        $locations = Location::all();
//        $this->locations = $locations;

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

        return view('location/locations')->with(array('locations' => $locations));
    }

    public function oauth(){
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

            $auth = json_decode((string)$response->getBody());

            //TODO: You'd typically save this payload in the session
            $this->accessToken = $auth->access_token;

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
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
    //TODO: improve validation for adding location (see update-location)
    public function store(Request $request)
    {
//        return view('admin_template');
//    }

        try {
            //validate data
//            $this->validate($request, [
//                'name' => 'required|unique:locations|max:255',
//                'address' => 'required|unique:locations|max:255',
//            ]);
//
//
//            //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field
//            // to type address and select from dropdown
//
//            dd($request);
//            //gather data from input fields
            $name = ucfirst(Input::get('name'));
            $address = Input::get('address');
            $geoCoords = $this->geoCode($address);
            $latitude = $geoCoords->results[0]->geometry->location->lat;
            $longitude = $geoCoords->results[0]->geometry->location->lng;
            $notes = ucfirst(Input::get('info'));

            //post request to api
            $this->oauth();

            //retrieve token needed for authorized http requests
            $token = $this->accessToken();

            $client = new GuzzleHttp\Client;
            $body = 'name='.$name.'&address='.$address.'&latitude='.$latitude.'&longitude='.$longitude.'&notes='.$notes;
//dd($body);
            //doesn't add to db, but goes smoothly
//            $response = $client->post('http://odinlite.com/public/api/locations', ['json' => ["access_token" => $token, 'name' => $name,
//                'address'=> $address,
//                'latitude' => $latitude,
//                'longitude'=>$longitude,
//                'notes'=> $notes,
//                ]
//            ]);
        $response = $client->post('http://odinlite.com/public/api/locations',[
                'form_params' => [
                'Authorization' => 'Bearer ' . $token,
                 ]
        ]);
//            ['body' => $body],[
//            'headers' => [
//                'Authorization' => 'Bearer ' . $token,
//                'Content-Type' => 'application/x-www-form-urlencoded',
//                'Accept'     => 'application/json',
//
//            ]
//                ]


//
//           dd($response);
//
////            $response = $client->send($request,  ['headers' => [
////                    'Authorization' => 'Bearer ' . $token,
////                ]
////            ]);
////            request('PUT', '/put', ['json' => ['foo' => 'bar']])
//
//            $apiResponse = json_decode((string)$response->getBody());
//
                dd($response);//null even when just returning success is true or false
//
//            //display confirmation page
            $theData = 'You have successfully added the location'.$name;
            return view('confirm-create')->with(array('theData' => $theData, 'url' => 'locations'));

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
                dd($e);
        }
    }
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
    public function show($id)
    {
        $this->oauth();

        //retrieve token needed for authorized http requests
        $token = $this->accessToken();

        $client = new GuzzleHttp\Client;

        $response = $client->get('http://odinlite.com/public/api/location/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);

        $location = json_decode((string)$response->getBody());

        return view('location/show')->with('location', $location);
    }

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
//        dd($location);
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
        $location = Location::find($id);
        try {
            //get the data from the form for validation against other location items, not this location being edited
            $locationName = ucfirst(Input::get('name'));
            $address = Input::get('address');
            $additional = ucfirst(Input::get('info'));

            //validate the data and store the data in the db if it is has been modified
            if(($locationName != $location->name)||($address != $location->address)||($additional != $location->additional_info)) {
                if ($locationName != $location->name) {
                    $this->validate($request, [
                        'name' => 'required|unique:locations|max:255',
                    ]);
                    $location->name = $locationName;
                }

                if ($address != $location->address) {
                    $this->validate($request, [
                        'address' => 'required|unique:locations|max:255'
                    ]);

                    $geoCoords = $this->geoCode($address);
                    $location->latitude = $geoCoords->results[0]->geometry->location->lat;
                    $location->longitude = $geoCoords->results[0]->geometry->location->lng;
                    $location->address = $address;
                }

                if ($additional != $location->additional_info) {
                    $location->additional_info = $additional;
                }
                $location->save();

                //display confirmation page
                $theAction = 'You have successfully edited the address. The address stored in the system is: ' . $address;
                return view('confirm')->with(array('theAction' => $theAction));
            }
            //no data changed, but save btn pressed
            else{
                $theAction = 'No changes were made to the location.';
                return view('confirm')->with(array('theAction' => $theAction));

            }
        } catch (\ErrorException $error) {
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
                $errors = collect($e);
                return view("location/edit-locations")->with(array('errors' => $errors, 'location' => $location));
            } else {
                return view("location/edit-locations")->with(array('errors' => 'Validation error found', 'location' => $location));
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

//        return view('location/locations')->with(array('locations' => $locations));
//
//        $location = Location::find($id);
            $theAction = 'You have successfully deleted the location';
//        Location::destroy($id);
            return view('confirm')->with('theAction', $theAction);

        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            dd($e);
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

//            $location = Location::find($id);
            $name = $location->name;
            $id = $location->id;
            $url = 'locations';
            return view('confirm-delete')->with(array('fieldDesc' => $name, 'id' => $id, 'url' => $url));
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            dd($e);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Form;
use Model;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //retrieve all records from locations table via Location Model
        $locations = Location::all();
        $this->locations = $locations;
        $location = $locations[0];//TODO: auto-select an item rather than hardcod
        //TODO: the first location in the sorted list will be in the top section of the webpage
        return view('location/locations')->with(array('locations' => $this->locations, 'displayItem' => $location));
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
        try {
            //validate data
            $this->validate($request, [
                'name' => 'required|unique:locations|max:255',
                'address' => 'required|unique:locations|max:255',
            ]);


            //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field
            // to type address and select from dropdown
            //store the data in the db
            $location = new Location;
            $location->name = ucfirst(Input::get('name'));
            $address = Input::get('address');
            $location->address = $address;
            $geoCoords = $this->geoCode($address);
            $location->latitude = $geoCoords->results[0]->geometry->location->lat;
            $location->longitude = $geoCoords->results[0]->geometry->location->lng;

            $location->additional_info = ucfirst(Input::get('info'));
            $location->save();

            //display confirmation page
            return view('confirm-create')->with(array('theData' => $address, 'entity' => 'Location', 'url' => 'locations'));

        } catch (\ErrorException $error) {
            if ($error->getMessage() == 'Undefined offset: 0') {
                $e = 'Please provide a valid address';
                $errors = collect($e);
                return view('location/create-locations')->with('errors', $errors);
            } else {
                return view("location/create-locations");
            }
        }
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
        $location = Location::find($id);
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
                $theAction = 'edited the address. The address stored in the system is: ' . $address;
                return view('confirm')->with(array('theAction' => $theAction));
            }
            //no data changed, but save btn pressed
            else{
                $theAction = 'made no changes';
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
        $location = Location::find($id);
        $theAction = 'deleted ' . $location->name;
        Location::destroy($id);
        return view('confirm')->with('theAction', $theAction);
    }

    public static function confirmDelete($id)
    {
        $location = Location::find($id);
        $name = $location->name;
        $id = $location->id;
        $url = 'locations';
        return view('confirm-delete')->with(array('fieldDesc' => $name, 'id' => $id, 'url' => $url));
    }

    public function geoCode($address)
    {
        $prepAddr = str_replace(' ', '+', $address);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&key=AIzaSyA1HtcSijw1F0mJRLpsr8ST5koG4T9_tew');
        $output = json_decode($geocode);
        return $output;

    }
}

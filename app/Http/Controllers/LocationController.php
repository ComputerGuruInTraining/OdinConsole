<?php

namespace App\Http\Controllers;

use Input;
use Form;
use Model;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Routing\Controller as BaseController;

//FIXME: line1 ?? LocationController seems to be an issue in web.php.
// FIXME: line2 ?? If a route to a resource is after the route to the location resource, the resource urls do not work.
// FIXME: line3 ?? Possible Cause: LocationController manually typed. So Possible Fix: create in cmd line and copy code into methods.

class LocationController extends Controller
{
    protected $locations;

//    public function __construct()
//    {
//
//    }

    public function index()
    {
        //retrieve all records from locations table via Location Model
        $locations = Location::all();
        $this->locations = $locations;
        $location = $locations[0];
        //TODO: sort the locations into a sorted array
        //TODO: the first location in the sorted list will be in the top section of the webpage
        return view('home/location/locations')->with(array('locations' => $this->locations, 'displayItem' => $location));

    }

    public function create()
    {
        return view('home/location/create-locations');
    }

    /*
 * Store a new location
 *
 * @param Request $request
 * @return Response (automatically generated if validation fails)
 */
//    TODO v1 high_priority: test geocodes and ensure they correspond to the selected address correctly.
    public function store(Request $request)
    {
        //validate data
        //TODO: validate address as complete so geoCode can be obtained
        //TODO: unique alias ie name
        $this->validate($request, [
           'name' => 'required|max:255',
        'mapData' => 'required|max:255',
        ]);

        //store the data in the db
        //TODO: v3 Improvement: Grab the address from the Marker Info Window so that user can select address on map as an alternative to using input field to type address and select from dropdown
        $location = new Location;
        $location->name = ucfirst(Input::get('name'));
        $address = Input::get('mapData');
        $location->address = $address;
        //TODO v1 or v2??: catch for incorrect addresses or addresses that can not be selected via map
        $geoCoords = $this->geoCode($address);
        $location->latitude = $geoCoords->results[0]->geometry->location->lat;
        $location->longitude = $geoCoords->results[0]->geometry->location->lng;
        $location->additional_info = ucfirst(Input::get('info'));
        $location->save();

        //display confirmation page
        return view('confirm')->with(array('theData'=> $address, 'theAction' => 'added'));
        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');
    }

    public function edit($id)
    {
        $location = Location::find($id);
        return view('home/location/edit-locations')->with('location', $location);
    }


    public static function select($id){
        $location = Location::find($id);
        $locations = Location::all();
        return view('home/location/locations')->with(array('locations' => $locations, 'displayItem' => $location));
    }

    public function update($id, Request $request){
        //find the location object
        $location = Location::find($id);

        //validate data
        //TODO: unique alias ie name
        $this->validate($request, [
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        //store the data in the db
        $locationName = ucfirst(Input::get('name'));
        $location->name = $locationName;
//        TODO v2: should edit address be a map??
        $address = ucfirst(Input::get('address'));
        $location->additional_info = ucfirst(Input::get('info'));
        $location->address = $address;
        //TODO: catch for incorrect addresses
        $geoCoords = $this->geoCode($address);
        $location->latitude = $geoCoords->results[0]->geometry->location->lat;
        $location->longitude = $geoCoords->results[0]->geometry->location->lng;
        $location->save();

        //display confirmation page
        return view('confirm')->with(array('theData'=> $locationName, 'theAction' => 'edited'));

        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');
    }

    public function destroy($id){
        $location = Location::find($id);
        Location::destroy($id);
        return view('confirm')->with(array('theData'=> $location->name, 'theAction' => 'deleted'));
    }

    public static function confirmDelete($id){
        $location = Location::find($id);
        return view('confirm-delete')->with('deleting', $location);
    }

    public function geoCode($address){
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyA1HtcSijw1F0mJRLpsr8ST5koG4T9_tew');
        $output = json_decode($geocode);
        return $output;
    }

//    public function setAddress(){
//        $auto = Input::get('autocomplete');
//
//        Input::merge(['address' => $auto]);
//        return view('confirm')->with('theData', $auto);
//    }




//  public function selectedLocation($dbLocation){
////      locations = Location::all();
//
//      //change the top section of the webpage
//
//      echo("<script>
//        var dbLocation = $dbLocation;
//        document.getElementById('list-item').innerHTML = dbLocation->name;
//
//
//
//        </script>\");
//
//      //return view('home/location/locations')->with(array('locations'=>$this->locations, 'displayItem'=>$dbLocation, 'controller' => $this));
//
////          'displayItem'=>));
//  }

//  public function getLocations(){
//
//      return $locations();
//  }
}

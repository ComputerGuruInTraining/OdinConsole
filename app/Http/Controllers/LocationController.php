<?php

namespace App\Http\Controllers;

use Input;
use Form;
use Model;
//use app\functions;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
//use App\Http\Controllers\Controller;
//use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;

class LocationController extends Controller
{
    protected $locations;
    protected $selected;

    public function __construct()
    {

    }

    public function index()
    {
        //retrieve all records from locations table via Location Model
        $locations = Location::all();
        $this->locations = $locations;
        $this->selected = $locations[0];
        //TODO: sort the locations into a sorted array
        //TODO: the first location in the sorted list will be in the top section of the webpage
        return view('home/location/locations')->with(array('locations' => $this->locations, 'displayItem' => $this->selected, 'controller' => $this));
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
        $location = new Location;
        $location->name = Input::get('name');
        $address = Input::get('mapData');
        $location->additional_info = Input::get('info');
        $location->address = $address;
        //TODO: catch for incorrect addresses or addresses that can not be selected via map
        //FIXME: HIGH v1 cater for enter being pressed when location selected from drop-down list on map input.
        //Atm: the form is submitted, but needs to not be submitted when enter pressed in map input field.
        //Could provide a message to user to not press enter to select the address at the very least.
        $geoCoords = $this->geoCode($address);
        $location->latitude = $geoCoords->results[0]->geometry->location->lat;
        $location->longitude = $geoCoords->results[0]->geometry->location->lng;
        $location->save();

        //display confirmation page
        return view('confirm')->with('theData', $location->address);
        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');
    }

    public function edit($id)
    {
        $location = Location::find($id);
        return view('home/location/edit-locations')->with('location', $location);
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
        $location->name = Input::get('name');
        $address = Input::get('address');
        $location->additional_info = Input::get('info');
        $location->address = $address;
        //TODO: catch for incorrect addresses
        $geoCoords = $this->geoCode($address);
        $location->latitude = $geoCoords->results[0]->geometry->location->lat;
        $location->longitude = $geoCoords->results[0]->geometry->location->lng;
        $location->save();

        //display confirmation page
        //TODO: change msg on confirm page
        $locationName = Input::get('name');
        return view('confirm')->with('theData', $locationName);

        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');
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


//  public function select(){
//      $this->selected = $this->locations[2];
//      echo("<script>console.log('PHP: ".$this->selected."');</script>");
//
////      return $selected;
//
//  }

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

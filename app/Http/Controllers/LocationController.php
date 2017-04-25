<?php

namespace App\Http\Controllers;

use Input;
use Form;
//use app\functions;
use App\Models\Location;
use Illuminate\Http\Request;
//use App/
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

        //TODO: the first location in the sorted list will be in the top section of the webpage ie sortedArray[0]

        return view('home/location/locations')->with(array('locations' => $this->locations, 'displayItem' => $this->selected, 'controller' => $this));
    }

    public function create()
    {
        //display the map with a search input field that has auto-complete capability

        return view('home/location/create-locations');
    }

    public function edit($id)
    {
        $location = Location::find($id);
        return view('home/location/edit-locations')->with('location', $location);
    }

    /*
     * Store a new location
     *
     * @param Request $request
     * @return Response (automatically generated if validation fails)
     */
//    public function doCreate(Request $request)
    public function doCreate()
    {

        //validate data
        //TODO: validate address as complete so geoCode can be obtained
        //TODO: unique alias ie name
//        $this->validate($request, [
//            'name' => 'required|max:255',
//            'address' => 'required|max:255',
//        ]);


        //store the data in the db
        $location = new Location;
//        $i = 0;
//        $name = 'Location '.$i;
//        $i++;
//      TODO: create a new column address_details (nullable) in the locations table & consider need for name column.
//      User probably doesn't need to name the location and perhaps no value added.
//      Until then, place address_details in name column.
        $location->name = Input::get('info');
        //cater for possibility of no further infomation being provided in additional address details input field.
        if($location->name == null){
            $location->name = "None Given";
        }
        $location->address = Input::get('address');
        $address = $location->address;
//        TODO: catch for incorrect addresses
//        get geoCoords from GeoCoding result, ask user if correct address, if not please enter correct address or proceed with address typed, but will be error displaying on map as address not recognized by the address validation service
        $geoCoords = $this->geoCode($address);
        $location->latitude = $geoCoords->results[0]->geometry->location->lat;
        $location->longitude = $geoCoords->results[0]->geometry->location->lng;
        $location->save();
        //display confirmation page
        $address = Input::get('address');
        return view('confirm')->with('theData', $address);

        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');
    }

//    public function setAddress(){
//        $auto = Input::get('autocomplete');
//
//        Input::merge(['address' => $auto]);
//        return view('confirm')->with('theData', $auto);
//    }

    public function update($id){
        //find the location object
        $location = Location::find($id);

        //validate data
        //TODO: check values have been changed before updating the record
        //TODO: validate address as complete so geoCode can be obtained
        //TODO: unique alias ie name
//        $this->validate($request, [
//            'name' => 'required|max:255',
//            'address' => 'required|max:255',
//        ]);

        //store the data in the db
//        $location = new Location;
        $location->name = Input::get('name');
        $location->address = Input::get('address');
        $address = Input::get('address');
//        TODO: catch for incorrect addresses
        $geoCoords = $this->geoCode($address);
        $location->latitude = $geoCoords->results[0]->geometry->location->lat;
        $location->longitude = $geoCoords->results[0]->geometry->location->lng;
        $location->save();
        //display confirmation page
        $locationName = Input::get('name');
        return view('confirm')->with('theData', $locationName);

        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');

//
//
//            return View::make('location.edit-locations', [ 'location' => $location ]);

    }
//        google maps api js key:
//    public function showMap(){
//        $map=file_get_contents('https://maps.googleapis.com/maps/api/js?key=AIzaSyD7xSpcb0ZqETybsCNdsyofP0Fmx_RurvQ&callback=initMap');
//        return $map;
//    }

    public function geoCode($address){
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyA1HtcSijw1F0mJRLpsr8ST5koG4T9_tew');
        $output = json_decode($geocode);
        return $output;
    }
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

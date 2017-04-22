<?php

namespace App\Http\Controllers;

use Input;
use Form;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
//use App\Http\Controllers\Controller;
//use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;

class LocationController extends Controller
{
    public function showLocations(){
        return view('home/location/locations');
    }

    public function createLocations(){
        return view('home/location/create_locations');
    }

    /*
     * Store a new location
     *
     * @param Request $request
     * @return Response (automatically generated if validation fails)
     */
    public function doCreate(Request $request){

        //validate data
        //TODO: validate address as complete so geoCode can be obtained
        $this->validate($request, [
            'name' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        //store the data in the db
        $location = new Location;
        $location->name = Input::get('name');
        $location->address = Input::get('address');
        $address = Input::get('address');
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
    }

  public function geoCode($address){
      $prepAddr = str_replace(' ','+',$address);
      $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyA1HtcSijw1F0mJRLpsr8ST5koG4T9_tew');
      $output = json_decode($geocode);
      return $output;
  }
}

<?php

namespace App\Http\Controllers;

use Input;
use Form;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;

class LocationController extends BaseController
{
    public function showLocations(){
        return view('home/location/locations');
    }

    public function createLocations(){
        return view('home/location/create_locations');
    }

    public function doCreate(){
        $location = new Location;
        $location->name = Input::get('name');
        $location->address = Input::get('address');
        //TODO: the address needs to be converted to a latitude and longitude
        $location->latitude = 'todolatitude';
        $location->longitude = 'todolongitude';
        $location->save();
        //display confirmation page
        $locationName = Input::get('name');
        return view('confirm')->with('theData', $locationName);

        //TODO: associate location with a client and perhaps group addresses. Modify form also
        //$client = Input::get('client');
        //$addressGroup = Input::get('address_group');
    }
}

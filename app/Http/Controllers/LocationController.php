<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;

class LocationController extends BaseController
{
    public function createLocations(){
        return view('home/location/create_locations');
    }

    public function doCreate(){
        //FIXME: setup the Location model properly
        $location = new Location;
        //from the form, only address can be added to the locations table
        //the address needs to be converted to a latitude and longitude
        $location->address = Input::get('address');

        $client = Input::get('client');//TODO: use the form data in $client
        $addressGroup = Input::get('address_group');//TODO use the form data in $addressGroup

        $location->save();
        //display confirmation page
        return View::make('layouts/confirm_layout');
    }

}

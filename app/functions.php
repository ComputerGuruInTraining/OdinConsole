<?php
/**
 * Created by PhpStorm.
 * User: Bernadette
 * Date: 23/04/2017
 * Time: 1:32 PM
 */
if(! function_exists('selectedLocation')){
    function selectedLocation($locations, $dbLocation) {
//      locations = Location::all();
    return view('home/location/locations')->with(array('locations' => $locations, 'displayItem' => $dbLocation));
    }
}
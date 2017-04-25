<?php

use App\Http\Controllers\LocationController;
//use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//   return view('admin_template');
//});

Route::get('/', 'HomeController@getIndex');

Route::post('home', 'HomeController@postIndex');

Route::get('/admin', function () {
    return view('admin_template');
});

Route::resource('/user', 'UserController');

Route::resource('/location', 'LocationController');

//get('url extension', 'ControllerName@functionName')
//Route::get('/locations', 'LocationController@showLocations');

//Route::get('/create-location', 'LocationController@createLocations');
//Route::get('/auto', 'LocationController@setAddress');
//TODO: change url
Route::post('/create-location', 'LocationController@doCreate');

//Route::put('/edit-location/', 'LocationController@update');
Route::put('/edit-location', 'LocationController@update');
//Route::get('/edit-location/'.$location->id, 'LocationController@editLocations');
//'/edit-location'. $location->id

//Route::post('/edit-location', 'LocationController@doEdit($location->id)');
//
//Route::post('/edit-location', array('as' => $location, function(){
//            return App::make('LocationController')->doEdit($location->id);
//
//
//}));
//Route::get('/locations', 'LocationController@selectedLocation($dbLocation)');

//Route::get('/locations', function(){
//    $locationView = LocationController::showLocations();
//    return $locationView;
//
//});

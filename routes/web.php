<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\RosterController;

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

Route::post('/', 'HomeController@postIndex');

Route::get('/admin', function () {
    return view('admin_template');
});

Route::resource('/user', 'UserController');

Route::resource('/add_user', 'UserController@store');

Route::get('/login', 'HomeController@getLogin');

Route::get('logout', 'HomeController@getLogout');

Route::resource('/employees', 'EmployeeController');

Route::get('/reports', 'ReportController@reportList');

Route::get('/clock', function(){
    return view('clock-picker');

});
//move folders (both roster and locations) and change paths and make consistent urls for locations at the same time
Route::resource('/rosters', 'RosterController');

//Route::get('rosters/create', function(){
//
//
//   return view('home/rosters/create');
//});

Route::resource('/locations', 'LocationController');

//Route::post('/location/created', 'LocationController@store');

//error if the url format is for eg Route::get('/location/{theId}/'....);
//Route::get('/{theURL}/', function($theURL){
//
//    $locationView = LocationController::select($theURL);
//    return $locationView;
//
//});

//Route::get('/{theId}/',  function($theId){
//
////    $theURL = URL::route//array('as' => 'selected',
//    $locationView = LocationController::select($theId);
//    return $locationView;
//
//});

Route::get('/confirm-delete-location/{theId}/',  function($theId){
    $deleteView = LocationController::confirmDelete($theId);
    return $deleteView;

});

Route::get('/confirm-delete-shift/{theId}/',  function($theId){
    $deleteView = RosterController::confirmDelete($theId);
    return $deleteView;
});




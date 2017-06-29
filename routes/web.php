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

//FIXME: get url from api worked with an empty model in api 1st attempt didn't work with a model with custom code, but 1st attempt was also in localhost so cause may not have been model.
//FIXME: cont. If url not working, need to check the model in api and test with empty model.

Route::get('/', 'HomeController@getIndex');

Route::post('/', 'HomeController@postIndex');

Route::get('/admin', function () {
    return view('admin_template');
});

Route::resource('/user', 'UserController');
Route::resource('employees', 'EmployeeController');

Route::get('/dashboard', 'DashboardController@index');
//Route::get('/dashboard', 'DashboardController@testFunction');
Route::resource('/add_user', 'UserController@store');

Route::get('/login', 'HomeController@getLogin');

Route::get('logout', 'HomeController@getLogout');

Route::resource('/employees', 'EmployeeController');

Route::resource('/reports', 'ReportController');

//Route::get('/reports', 'ReportController@reportList');
//
//Route::get('/reports/create', 'ReportController@create');

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

//Route::get('/location/add', 'LocationController@create');

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




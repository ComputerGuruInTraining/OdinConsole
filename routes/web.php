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

//Route::get('/clock', function(){
//    return view('clock-picker');
//
//});

Route::resource('/rosters', 'RosterController');

Route::resource('/locations', 'LocationController');

//global confirm-delete view
Route::get('confirm-delete/{id}/{entity}', function($id, $entity){
    $confirmView = confirmDlt($id, $entity);
    return $confirmView;
});

Route::get('/confirm-delete-location/{theId}/',  function($theId){
    $deleteView = LocationController::confirmDelete($theId);
    return $deleteView;

});

Route::get('/confirm-delete-shift/{theId}/',  function($theId){
    $deleteView = RosterController::confirmDelete($theId);
    return $deleteView;
});




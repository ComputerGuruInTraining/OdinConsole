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

Route::post('/location/created', 'LocationController@store');

//error if the url format is for eg Route::get('/location/{theId}/'....);
Route::get('/{theId}/location/', function($theId){

    $locationView = LocationController::select($theId);
    return $locationView;

});

<?php

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

//Route::get('/admin', function () {
//    return view('admin_template');
//});

Route::get('/', 'HomeController@getIndex');
//Route::get('/', 'HomeController@getIndex');

Route::post('/', 'HomeController@postIndex');


//Route::controller('/', 'HomeController');
//user route
Route::resource('/user', 'UserController');

//login and logout route
Route::get('login', 'HomeController@getLogin()');

Route::get('Logout', 'HomeController@getLogout()');

//user route
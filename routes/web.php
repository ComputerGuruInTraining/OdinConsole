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

Route::get('/admin', function () {
    return view('admin_template');
});
//Route::get('/', function () {
//    return view('admin_template');
//});

//Route::get('home', 'HomeController@getIndex');
Route::get('/', 'HomeController@getIndex');

Route::post('home', 'HomeController@postIndex');

Route::resource('/user', 'UserController');
//Route::resource('/user', 'UserController');
//Route::controller('/', 'HomeController');
//Route::controller('/login', 'HomeController@getIndex');

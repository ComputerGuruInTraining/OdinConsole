<?php

use App\Http\Controllers\UserController;

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

Route::resource('/employees', 'EmployeeController');

Route::resource('/case-notes', 'CaseNoteController');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/login', 'HomeController@getLogin');

Route::get('logout', 'HomeController@getLogout');

Route::resource('/reports', 'ReportController');

Route::resource('/rosters', 'RosterController');

Route::resource('/locations', 'LocationController');

//global confirm-delete view
Route::get('confirm-delete/{id}/{url}', function($id, $url){
    if (session()->has('token')) {
        //confirmDlt defined in functions.php
        $confirmView = confirmDlt($id, $url);
        return $confirmView;
    }
});

//route for when the Forgot Password? Link pressed on Login page
Route::get('/reset/link', function(){
    return view('home/reset/forgot_pw');
});

//route for when the Send Reset Password Link pressed on forgot_pw page
Route::get('/reset/pw', 'ForgotPWController@resetPW');

//route to show Settings page when the Settings btn is pressed eg via Header Avatar Dropdown
Route::get('company/settings', 'UserController@index');




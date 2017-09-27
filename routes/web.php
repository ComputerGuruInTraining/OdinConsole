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


Route::resource('/case-notes', 'CaseNoteController');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/login', 'HomeController@getLogin');

Route::get('logout', 'HomeController@getLogout');

//manual route to fix nav via header problem caused by the url format
Route::get('/report-{id}', 'ReportController@show');

Route::get('/pdf-{id}', 'ReportController@view')->name('pdf');

Route::resource('/reports', 'ReportController');

Route::resource('/rosters', 'RosterController');

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
Route::get('settings', 'UserController@index');

Route::get('/register', 'UserController@registerCompany');

Route::post('/register/company', 'UserController@postRegister');

// TODO: Implement app wide for better navigation
//manually define routes in order for sidebar and header toggle functionality to work

//Route::get('employee-create', 'EmployeeController@create');
//
//Route::get('reporting', 'ReportController@create');
//


//Route::resource('/locations', 'LocationController');
//Following all work and are implemented in app
Route::get('location', 'LocationController@index');

Route::get('location-create', 'LocationController@create');

Route::post('location-created', 'LocationController@store');

Route::put('location-updated-{id}', 'LocationController@update');

Route::get('location-edit-{id}', 'LocationController@edit');

Route::get('location-show', 'LocationController@show')->name('location-show');

//in process of manually overriding auto routes created by using ResourceController
//to combat the header navigation error which is caused by the route format


Route::get('employees', 'EmployeeController@index');
//FIXME 1a: date picker does not work for urls in the format /employee-create
//FIXME 1b: but header nav does not work for urls in the format employee/create
//Route::get('employee-tocreate', 'EmployeeController@create');

//Route::get('employee-create', 'EmployeeController@create');
//
//Route::post('employee-created', 'EmployeeController@store');

Route::get('employee-edit-{id}', 'EmployeeController@edit');

Route::put('employee-updated-{id}', 'EmployeeController@update');

Route::resource('/employees', 'EmployeeController', ['except' => [
    'edit', 'update', 'index'
]]);

//global confirm-delete view
Route::get('confirmdel-{id}-{url}', function($id, $url){
    if (session()->has('token')) {
        //confirmDlt defined in functions.php
        $confirmView = confirmDel($id, $url);
        return $confirmView;
    }
});

Route::delete("location-deleted-{id}", 'LocationController@destroy');

Route::get('/pdfSave-{id}', 'ReportController@pdfSave');

Route::get('/pdfview', 'ReportController@pdfView')->name('pdfview');


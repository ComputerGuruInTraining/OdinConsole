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

/* Dead links
 *
 * Add Dropdown:
 * Add Dropdown breaks menu-toggle and odin logo link when placed next to them in header
menu dropdown doesn't work when on the add-employees page
menu dropdown doesn't work when on the add-shift page
menu dropdown doesn't work when on the generate report page*/

Route::get('/', 'HomeController@getIndex');

Route::post('/', 'HomeController@postIndex');

Route::get('/admin', 'DashboardController@index');

//generic error exception msg route
Route::get('/error-msg', function(){
    if (session()->has('token')) {
        $msg = Config::get('constants.ERROR_GENERIC');
        return view('error-msg')->with('msg', $msg);
    }
});

//generic server error msg route
Route::get('/error-page', function(){
    if (session()->has('token')) {
        $msg = Config::get('constants.ERROR_SERVER');
        return view('error-msg')->with('msg', $msg);
    }
});

//download case notes image, calls download function in functions.php
Route::get('/download/{foldername}/{filename}', 'CaseNoteController@download');

Route::resource('/user', 'UserController');

Route::resource('/case-notes', 'CaseNoteController');

Route::get('/map-geolocation', 'DashboardController@index');

Route::get('/login', 'HomeController@getLogin');

Route::get('/logout', 'HomeController@getLogout');

Route::get('/report-{id}', 'ReportController@show');

Route::get('/pdf-{id}', 'ReportController@view')->name('pdf');

Route::resource('/reports', 'ReportController', ['except' => [
    'edit', 'update'
]]);

Route::resource('/rosters', 'RosterController');

Route::resource('/employees', 'EmployeeController');

Route::get('employee/create-existing', 'EmployeeController@selectUser');

Route::post('/employees/create-existing-user', 'EmployeeController@createExisting');

Route::post('/employee/create-existing/{userId}', 'EmployeeController@storeExisting');

//global confirm-delete view
Route::get('confirm-delete/{id}/{url}', function($id, $url){
    if (session()->has('token')) {
        //confirmDlt defined in functions.php
        $confirmView = confirmDlt($id, $url);
        return $confirmView;
    }
});

//global confirm-delete view
Route::get('/delete/{urlCancel}/{id}/{reportId}', function($urlCancel, $id, $reportId){
    if (session()->has('token')) {
        //confirmDlt defined in functions.php
        $confirmView = confirmDltReportCaseNote($id, $urlCancel, $reportId);
        return $confirmView;
    }
});

Route::get('reports-{id}-edit', 'ReportController@edit');

Route::get('/edit-case-notes/{caseNoteId}/reports/{reportId}', 'ReportController@editCaseNote');

Route::put('/report/{caseNoteId}/{reportId}', 'ReportController@update')->name('updateReportCase');

//id parameter is the id of the case note
Route::delete("report/{reportId}/delete/{id}", 'ReportController@destroyCaseNote');

//route for when the Forgot Password? Link pressed on Login page
Route::get('/reset/link', function(){
    return view('home/reset/forgot_pw');
});

//route for when the Send Reset Password Link pressed on forgot_pw page
Route::get('/reset/pw', 'ForgotPWController@resetPW');

//route to show Settings page when the Settings btn is pressed eg via Header Avatar Dropdown
Route::get('/settings', 'UserController@index');

Route::get('/register', 'UserController@registerCompany');

Route::post('/register/company', 'UserController@postRegister');

Route::get('location', 'LocationController@index');

Route::get('location-create', 'LocationController@create');

Route::post('location-created', 'LocationController@store');

Route::post('/location-create-confirm', 'LocationController@confirmCreate');

Route::put('location-updated-{id}', 'LocationController@update');

Route::put('/location-edit-confirm-{id}', 'LocationController@confirmEdit');

Route::get('location-edit-{id}', 'LocationController@edit');

Route::get('location-show-{id}', 'LocationController@show')->name('location-show');

Route::delete("location-deleted-{id}", 'LocationController@destroy');

Route::get("/location-back", 'LocationController@confirmCreateCancel');

Route::get('location-back-{id}', 'LocationController@confirmEditCancel');

//global confirm-delete view
Route::get('confirmdel-{id}-{url}', function($id, $url){
    if (session()->has('token')) {
        //confirmDlt defined in functions.php
        $confirmView = confirmDel($id, $url);
        return $confirmView;
    }
});

Route::get('/pdfSave-{id}', 'ReportController@pdfSave');

Route::get('/pdfview', 'ReportController@pdfView')->name('pdfview');

Route::get('/support', 'DashboardController@support');

Route::get('/privacy', 'DashboardController@privacy');

//go back to previous page global route
Route::get('cancel-delete', function(){
    if (session()->has('token')) {

//        return redirect()->back()->getTargetUrl();
//        return redirect()->getUrlGenerator()->previous();//prints it on screen
        return redirect()->back();//refreshes page
    }
});

Route::get('/support/users', 'HomeController@support');

/*Routes Used??? TODO: check*/
Route::get('/laravel-pdf', 'ReportController@generate')->name('laravelPdf');

Route::post('/webhooks/failed', 'UserController@failedEmail');



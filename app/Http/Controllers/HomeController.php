<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp;
use Form;
use Config;

class HomeController extends BaseController
{

    public function getIndex()
    {
        try {

            return View::make('home.index');

        }catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');
        }
    }

    public function postIndex()
    {
        try {
            $username = Input::get('username');
            $password = Input::get('password');

            //oauth2 fn will validate only for those users in the user_roles table
            //outh2 is defined in App/Utilities/functions.php
            if (oauth2($username, $password)) {
                return Redirect::intended('/admin');
            }
            //else, not api authenticated so user credentials not valid
            return Redirect::back()
                ->withInput()
                ->withErrors('Login Denied: Either email/password combo does not exist or you do not have access. 
            Please ensure the account has been activated as this could be the problem.');
        }catch(\Exception $exception){
            $errMsg = $exception->getMessage();

            $e = Config::get('constants.INTERNET_ERROR');
            $eConnSer = Config::get('constants.CONN_SERVER_ERROR');

            if((strpos($errMsg, 'Could not resolve host') !== false)){

                return Redirect::back()
                    ->withErrors($e);

            } else{
                //default of redirect::back will be fine if redirect to a static page, so use error page as default within app

                return Redirect::back()
                ->withErrors($eConnSer);
            }
        }

    }

    public function getLogin()
    {
        return Redirect::to('/');
    }

    public function getLogout()
    {
        Auth::logout();

        session()->flush();

        return Redirect::to('/');
    }

    public function support(){

        try {
                if (session()->has('token')) {

                    $loggedIn = true;

                    return view('layouts.tabs.master_tabs_private')->with('loggedIn', $loggedIn);

                }else{

                    return Redirect::to('/');
                }
            }catch (GuzzleHttp\Exception\BadResponseException $e) {
                $err = 'Error displaying case notes';
                return view('error-msg')->with('msg', $err);

            } catch (\ErrorException $error) {
                $e = 'Error displaying case notes page';
                return view('error-msg')->with('msg', $e);

            } catch (\Exception $err) {
                $e = 'Unable to display case notes';
                return view('error-msg')->with('msg', $e);

            } catch (\TokenMismatchException $mismatch) {
                return Redirect::to('login')
                    ->withInput()
                    ->withErrors('Session expired. Please login.');

            } catch (\InvalidArgumentException $invalid) {
                $error = 'Error loading case notes';
                return view('error-msg')->with('msg', $error);
            }
    }

}

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


class HomeController extends BaseController
{
//    public $token;
//
//    public function _construct($token){
//        $this->token = $token;
//
//    }

    public function getIndex()
    {
        return View::make('home.index');
    }

    public function postIndex()
    {

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
            ->withErrors('Error: Either email/password combo does not exist or you do not have access. 
            Please ensure the account has been activated as this could be the problem.');
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

}

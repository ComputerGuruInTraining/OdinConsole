<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\View;
//use View;

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

        if(oauth2($username, $password)){
            return Redirect::intended('/admin');
        }
        //else, not api authenticated so user credentials not valid
		return Redirect::back()
			->withInput()
			->withErrors('That username/password combo does not exist.');
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


//	public static function  setToken($token2){
//	    $token = $token2;
//
//    }
//
//    public function getToken(){
//
//	    return $this->token;
//    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Support\Facades\View;
//use View;

class HomeController extends BaseController
{

	public function getIndex()
	{ 
		//return View::make('home.index');
		return view('home.index');
	}

	public function postIndex()
	{
		
		$username = Input::get('username');
		$password = Input::get('password');

		if (Auth::attempt(['username' => $username, 'password' => $password]))
		{
			return Redirect::intended('/user');
		}

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

		return Redirect::to('/');
	}

	public function showLocations(){
		return view('locations');
	}

}

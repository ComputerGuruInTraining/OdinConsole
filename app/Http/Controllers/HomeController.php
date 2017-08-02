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
use GuzzleHttp;
use Illuminate\Support\Facades\Hash;


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
            ->withErrors('Error: Either email/password combo does not exist or you do not have access.');
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

    public function registerCompany()
    {
        return view('home.register');
    }

    public function postRegister(Request $request)
    {
        try {

            $client = new GuzzleHttp\Client;

            $company = $request->input('company');
            $owner = $request->input('owner');
            $first = $request->input('first');
            $last = $request->input('last');
            $emailUser = $request->input('emailUser');
            $pw = str_random(10);
            $password = Hash::make($pw);

//            dd($company, $owner, $email, $first, $last, $emailUser);
            $response = $client->post('http://odinlite.com/public/company', array(
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array('company' => $company, 'owner' => $owner,
                        'first_name' => $first, 'last_name' => $last, 'email_user' => $emailUser, 'pw' => $password
                    )
                )
            );

            $company = json_decode((string)$response->getBody());

          //  dd($company);

            if ($company->success == true) {

                $theAction = 'The company account has been created and an email has been sent to ' . $emailUser . ' to complete the registration process.
                The Odin Team welcomes you on board and we trust that you will enjoy the experience our app provides.';

                return view('/confirm')->with('theAction', $theAction);

            } else {
                return view('home.register');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          //  echo $e;
            $err = 'Please provide a valid email.';
            $errors = collect($err);
            return view('home.register')->with('errors', $errors);
        } catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
            $e = 'Please fill in all required fields';
            $errors = collect($e);
            return view('home.register')->with('errors', $errors);

        }
    }

}

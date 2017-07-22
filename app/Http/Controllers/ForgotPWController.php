<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;

class ForgotPWController extends Controller
{
    public function resetPW(Request $request){

        if($request->has('email')){
            $email = $request->input('email');
        }

        //request to api to send an email to the user with the reset password link
        $client = new GuzzleHttp\Client;

        $response = $client->post('http://odinlite.com/public/console/password/email', array(
                'headers' => array(
                    'Content-Type' => 'application/json'
                ),
                'json' => array('email' => $email
                )
            )
        );

        $result = json_decode((string)$response->getBody());

        if($result->success == true){
            $msg = 'Please check your inbox and junk email folder for the email that authenticates that you would like to reset your password';
        }
        else if($result->success == false){
            $msg = 'The password reset has not been successful. Please ensure the email address you have provided is correct';
        }

        return view('confirm')->with('theAction', $msg);
    }
}

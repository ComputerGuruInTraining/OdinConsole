<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Redirect;

class ForgotPWController extends Controller
{
    public function resetPW(Request $request)
    {

        try {
                if ($request->has('email')) {
                    $email = $request->input('email');
                }

                //request to api to send an email to the user with the reset password link
                $client = new GuzzleHttp\Client;

                $response = $client->post('http://odinlite.com/public/password/email', array(
                        'headers' => array(
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('email' => $email
                        )
                    )
                );

                $result = json_decode((string)$response->getBody());

                if ($result->success == true) {
                    $msg = 'Please check your inbox and junk email folder for the email that authenticates that you would like to reset your password';
                    return view('confirm')->with('theAction', $msg);

                } else if ($result->success == false) {
                    //this catches for the an invalid email reset pw request
                    $e = 'The password reset has not been successful. Please ensure the email address you have provided is correct';
                    $errors = collect($e);
                    return view('home/reset/forgot_pw')->with('errors', $errors);
                }

        } catch (\ErrorException $error) {
            //this catches for the an invalid email reset pw request
            $e = 'The password reset has not been successful. Please ensure the email address you have provided is correct';
            $errors = collect($e);
            return view('home/reset/forgot_pw')->with('errors', $errors);
        }
    }
}

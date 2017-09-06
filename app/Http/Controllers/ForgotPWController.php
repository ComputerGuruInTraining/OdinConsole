<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Redirect;
use Config;

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

                $response = $client->post(Config::get('constants.STANDARD_URL').'user/new/pw', array(
                        'headers' => array(
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array(
                            'email' => $email
                        )
                    )
                );

                $result = json_decode((string)$response->getBody());

                if ($result->success == true) {
                    return Redirect::back()
                        ->withErrors('An email has been sent to authenticate that you would like to 
                    reset your password. Please be sure to check your junk email folder.');

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
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            //this catches for the an invalid email reset pw request where the email does not exist and the error occurs at the server
            //when attempting to send an email
            return Redirect::back()
                ->withErrors('The password reset has not been successful. 
                Please ensure the email address you have provided is correct.');
        }
    }
}

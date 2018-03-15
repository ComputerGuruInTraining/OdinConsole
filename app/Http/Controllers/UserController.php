<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Form;
use Model;
use GuzzleHttp;
use Psy\Exception\ErrorException;
use Redirect;
use Hash;
use Config;
use DateTime;

class UserController extends Controller
{

	/**
	 * Display a listing of the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->get(Config::get('constants.API_URL').'user/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $users = json_decode((string)$response->getBody());

                $resp = $client->get(Config::get('constants.API_URL').'company/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $compInfo = json_decode((string)$resp->getBody());

                $jsonResponse = $client->get(Config::get('constants.API_URL').'subscription/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                //response could be either 1) $subscriptionStatus->trial = false or true, $subscriptionStatus->trial_ends_at = date if inTrial
                //or 2)$subscriptionStatus->subscriptions (entries in db)
                $subscriptionStatus = json_decode((string)$jsonResponse->getBody());

//                dd($subscription);//null at this point. why not false??? must count>0! ah because only 1 user ! ugh//fixme

                $users = array_sort($users, 'last_name', SORT_ASC);

                $url = 'user';

                if(isset($subscriptionStatus->trial)){

                    if(isset($subscriptionStatus->trial_ends_at)) {

                        //convert date object to string
                        //format as friendly string


//                        $date = date_create($subscriptionStatus->trial_ends_at);

                        //$subscriptionStatus->trial_ends_at is an object of stdclass
                        //php manual advises will json_encode to a simple js object
                        //although the result here is a string in the json format

                        $date = json_encode($subscriptionStatus->trial_ends_at);//$date = a json string

                        $json = json_decode($date, true);

//                        dd($json, $json['date']);

                        $date = formatDates($json['date']);

//                        dd($date);

//                        dd(gettype($subscriptionStatus->trial_ends_at), $subscriptionStatus->trial_ends_at);

                        return view('company-settings.index')->with(array(
                            'users' => $users,
                            'compInfo' => $compInfo,
                            'url' => $url,
                            'subscriptionStatus' => $subscriptionStatus->trial,
                            'trialEndsAt' => $date
                        ));

                    }
//                    dd($subscriptionStatus->trial,  $subscriptionStatus->trial_ends_at);


                    return view('company-settings.index')->with(array(
                        'users' => $users,
                        'compInfo' => $compInfo,
                        'url' => $url,
                        'subscriptionStatus' => $subscriptionStatus->trial));

                }else if(isset($subscriptionStatus->subscriptions)){

//                    dd($subscriptionStatus->subscriptions);

                    return view('company-settings.index')->with(array(
                        'users' => $users,
                        'compInfo' => $compInfo,
                        'url' => $url,
                        'subscriptionStatus' => $subscriptionStatus->subscriptions));//array

                }

//                return view('company-settings.index')->with(array(
//                    'users' => $users,
//                    'compInfo' => $compInfo,
//                    'url' => $url));

            }
            //user does not have a token
            else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying users';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying user page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display users';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');


        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading users';
            return view('error-msg')->with('msg', $error);
        } catch(\handleViewException $handle){
            $error = 'Error displaying page';
            return view('error-msg')->with('msg', $error);

        }
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create()
	{
	    try {
            if (session()->has('token')) {
                return view('user/create');
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('/company/settings')->withErrors('Error displaying users');
        }//error returned to laravel and caught
        catch (\ErrorException $error) {
            return Redirect::to('/company/settings')->withErrors('Error loading users page');
        } catch (\Exception $err) {
            return Redirect::to('/company/settings')->withErrors('Error displaying users page');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');


        } catch (\InvalidArgumentException $invalid) {
            return Redirect::to('/company/settings')->withErrors('Error displaying list of users');
        }
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        try {
            if (session()->has('token')) {

                //validate input meet's db constraints
                $this->validate($request, [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'role' => 'required'
                ]);

                //data to be inserted into db
                $first_name = ucwords(Input::get('first_name'));
                $last_name = ucwords(Input::get('last_name'));
                $email = Input::get('email');
                $role = Input::get('role');

                //api request variables
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->post(Config::get('constants.API_URL').'user', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'email' => $email, 'company_id' => $compId, 'role' => $role
                        )
                    )
                );
                $reply = json_decode((string)$response->getBody());

                    //Redirect user based on success or failure of db insert
                if($reply->success == true) {
                    $msg = 'You have successfully added '.$first_name.' '.$last_name.' to the system. 
                    Please advise the new user that an email has been sent to the email provided to complete the registration process. 
                    They may action this at their convenience';
                    //display confirmation page
                    return view('confirm-create-general')->with(array('theMsg' => $msg, 'url' => 'user', 'btnText' => 'Add User'));
                }
                else{
                    $err = 'Error creating user. Please ensure email is valid.';
                    //view expects a collection, so convert datatype to a collection
                    $errors = collect($err);
                    return view('user/create')->with('errors', $errors);
                }

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('user/create')
                ->withInput()
                ->withErrors('Operation failed. Please ensure input valid and email unique.');

        } catch (\ErrorException $error) {
            return Redirect::to('user/create')
                ->withInput()
                ->withErrors('Error storing user');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('user/create')
                ->withInput()
                ->withErrors('Error storing user details. Please check input is valid.');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        }
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->get(Config::get('constants.API_URL').'user/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $user = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($user == false) {

                    return verificationFailedMsg();
                }

                $userRole = getUserRole($id);

                return view('user/edit')->with(array('user' => $user, 'role' => $userRole));

            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying user details';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying user details for editing';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Error displaying user details for update';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');


        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading edit user page';
            return view('error-msg')->with('msg', $error);
        }
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                //validate input meet's db constraints
                $this->validate($request, [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'role' => 'required'
                ]);

                $token = session('token');

                //get the data from the form
                $first_name = ucwords(Input::get('first_name'));
                $last_name = ucwords(Input::get('last_name'));
                $email = Input::get('email');
                $role = Input::get('role');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL').'user/'.$id.'/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json',
                            'X-HTTP-Method-Override' => 'PUT'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'email' => $email, 'role' => $role
                        )
                    )
                );

                $user = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($user == false) {

                    return verificationFailedMsg();
                }

                //direct user based on whether record updated successfully or not
                if($user->success == true)
                {
                    $msg = 'You have successfully edited '.$first_name.' '.$last_name;
                    //display confirmation page
                    return view('confirm')->with('theAction', $msg);
                }
                else {
                    return Redirect::to('company/settings')->withErrors('Error updating user. Please ensure email is valid.');
                }
            }
            //user does not have a valid token
            else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('/user/'.$id.'/edit')
                ->withInput()
                ->withErrors('Operation failed. Please check input and ensure email unique.');

        } catch (\ErrorException $error) {
            return Redirect::to('/user/'.$id.'/edit')
                ->withInput()
                ->withErrors('Error updating user details. Please check input valid and email unique.');

        } catch (\InvalidArgumentException $err) {
            return Redirect::to('/user/'.$id.'/edit')
                ->withInput()
                ->withErrors('Error updating user. Please check input is valid.');

        }
        catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        }
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $response = $client->post(Config::get('constants.API_URL').'user/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'X-HTTP-Method-Override' => 'DELETE'
                    ]
                ]);

                $user = json_decode((string)$response->getBody());

                //ie record and user belong to different companies, therefore user not verified
                if ($user == false) {

                    return verificationFailedMsg();

                } else if(isset($user->primaryContact)){

                    return refuseDeleteMsg($user->primaryContact, 'user');

                } else if($user->success == true) {
                    $sessionId = session('id');

                    if($id == $sessionId){
                        return Redirect::to('/logout');
                    }else {
                        $msg = 'You have successfully deleted the user';
                        //display confirmation page
                        return view('confirm')->with('theAction', $msg);
                    }
                } else {
                    return Redirect::to('/settings')->withErrors('Failed to delete user');
                }
            }
            //user does not have a valid token
            else {
                return Redirect::to('/login');
            }
        }//api error
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('/settings')->withErrors('Error deleting user');
        }//error returned to laravel and caught
        catch (\ErrorException $error) {
            return Redirect::to('/settings')->withErrors('Error deleting the user');
        } catch (\Exception $err) {
            return Redirect::to('/settings')->withErrors('Error deleting user from database');

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');


        } catch (\InvalidArgumentException $invalid) {
            return Redirect::to('/settings')->withErrors('Error deleting user from system');
        }
	}

    /*
     *
     * Register Page
     *
    */

    public function registerCompany($trial = null)
    {
        return view('home.register')->with('trial', $trial);
    }

    public function postRegister(Request $request)
    {
        try {
            //validate input meet's db constraints
            $this->validate($request, [
                'company' => 'required|max:255',
                'owner' => 'nullable|max:255',
                'emailUser' => 'required|email|max:255',
                'password' => 'required|min:6|confirmed|max:15',
                'first' => 'required|max:255',
                'last' => 'required|max:255',
            ]);
//            dd($request);


            $company = $request->input('company');
            $first = $request->input('first');
            $last = $request->input('last');
            $emailUser = $request->input('emailUser');
            $pw = $request->input('password');

            //initialize in case no value input by user
            $owner = null;

            //optional field
            if($request->has('owner')){
                $owner = $request->input('owner');
            }

            //initialise the value for the requests that do not have a value in stripeToken ie free trials
            $stripeToken = null;

            if($request->has('stripeToken')) {
                $stripeToken = $request->stripeToken;
            }

//            dd($company, $first);

            $client = new GuzzleHttp\Client;

            $url = Config::get('constants.STANDARD_URL');

            $response = $client->post($url.'company', array(
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array('company' => $company, 'owner' => $owner,
                        'first_name' => $first, 'last_name' => $last, 'email_user' => $emailUser,
                        'pw' => $pw, 'stripeToken' => $stripeToken
                    )
                )
            );

            $company = json_decode((string)$response->getBody());

            if ($company->success == true) {

                $msgLine1 = 'The company account has been created and an email has been sent to ' . $emailUser . ' to 
                complete the registration process.';

                $msgLine2 =  'The ODIN Case Management team welcomes you on board and we trust that you will enjoy the experience our app provides.';

                return view('/confirm_alt')->with(array('title' => 'Confirmation of Success', 'line1' => $msgLine1, 'line2' => $msgLine2));

            } else {
                //success == false
                if($company->nonUnique == "Not Unique"){
                    //email already exists and registration will not occur
                    return Redirect::back()
                        ->withInput()
                        ->withErrors('Registration has not been successful at this time because the email address provided is already
                        stored in the system.  <br> If you have previously registered and have not received the activation email, 
                        please contact the support team.');
                }else{
                    //uncertain this would even occur, mostly an error will be thrown and caught below, but as a back up
                    return Redirect::back()
                        ->withInput()
                        ->withErrors('Registration has not been successful at this time. Please check your input and try again');
                }
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::back()
                ->withInput()
                ->withErrors('There is an error in the input. 
            This could be caused by an invalid email or 
            an email that already exists in the system. Please check your input.');
        } catch (\ErrorException $error) {
            dd($error);
            return Redirect::back()
                ->withInput()
                ->withErrors('Unable to complete the request. 
                Please check you have provided all required input as this may be the cause.');
        }catch (\InvalidArgumentException $err) {
            return Redirect::back()
                ->withInput()
                ->withErrors('Unable to complete the request. Please check input is valid as this may 
                 be the cause.');
        }
    }

    public function failedEmail(Request $request){

        $event = $request->input('event');

        $recipient = $request->input('recipient');

        if($request->has('message-headers')) {

            $textMsgHeaders = $request->input('message-headers');
            $jsonMsgHeaders = json_decode($textMsgHeaders);

            if (isset($jsonMsgHeaders->subject)) {

                $result = storeErrorLog($event, $recipient, $textMsgHeaders);
                return 'post successful';
            }
        }

            $result = storeErrorLog($event, $recipient);

            return 'post successful';
    }

    public function upgrade(){
        try {
            if (session()->has('token')) {

                //retrieve id from session data
                $id = session('id');

                $user = getUser($id);

                $email = $user->email;

                return view('company-settings/upgrade')->with(array(
                    'email'=> $email,
                    'selected' => null,
                    'chosenTerm' => null,
                    ));

            }//user does not have a token
            else {
                return Redirect::to('/login');
            }

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying subscription plan';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying subscription page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display subscription plans';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading subscription page';
            return view('error-msg')->with('msg', $error);

        } catch(\handleViewException $handle){
            $error = 'Error displaying page';
            return view('error-msg')->with('msg', $error);

        }
    }

    public function paymentUpgrade(Request $request){
        try {
            if (session()->has('token')) {

                //use stripeEmail returned from payment request
                $email = $request->stripeEmail;

                //todo: atm , assumed successful
                $confirm = 'Plan successfully updated. Receipt for the payment has been emailed to '.$email;
//                $confirm = 'Plan failed to update.'; + reason

                return view('company-settings/upgrade')
                    ->with(array(
                        'email'=> $email,
                        'confirm' => $confirm,
                        'selected' => null,
                        'chosenTerm' => null,
//                        'current' => ,
//                        'modified' => $modified
                    ));

            }//user does not have a token
            else {
                return Redirect::to('/login');
            }

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying subscription plan';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying subscription page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display subscription plans';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading subscription page';
            return view('error-msg')->with('msg', $error);

        } catch(\handleViewException $handle){
            $error = 'Error displaying page';
            return view('error-msg')->with('msg', $error);

        }




//        dd('submitted', $request->plan, $request->period, $request->stripeToken);//works

    }


    //return the view "upgrade_layout.blade.php" with the checkout widget in the foreground (so initiate the btn click)
    public function upgradePlan($plan, $term){
        try {
            if (session()->has('token')) {

//                dd($plan, $term);

                //retrieve id from session data
                $id = session('id');

                $user = getUser($id);

                $email = $user->email;

                return view('company-settings/upgrade')
                    ->with(array(
                    'email'=> $email,
                    'selected' => $plan,
                    'chosenTerm' => $term,
//                        'current' => ,
//                        'modified' => $modified
                ));

            }//user does not have a token
            else {
                return Redirect::to('/login');
            }

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying subscription plan';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying subscription page';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display subscription plans';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading subscription page';
            return view('error-msg')->with('msg', $error);

        } catch(\handleViewException $handle){
            $error = 'Error displaying page';
            return view('error-msg')->with('msg', $error);

        }


    }

//    public function test(){
//
//        return view('home.test');
//    }

}

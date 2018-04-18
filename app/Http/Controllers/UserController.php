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
use Carbon\Carbon;


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

                $currentUser = session('name');

                $url = 'user';

                //Users tab
                $response = $client->get(Config::get('constants.API_URL') . 'user/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $users = json_decode((string)$response->getBody());

                $users = array_sort($users, 'last_name', SORT_ASC);

                //retrieve id from session data
                $id = session('id');

                $user = getUser($id);

                $email = $user->email;

                //company tab
                $resp = $client->get(Config::get('constants.API_URL') . 'company/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $compInfo = json_decode((string)$resp->getBody());

                $subscription = getSubscription();

                //trial
                $inTrial = $subscription->get('inTrial');
                $trialEndsAt = $subscription->get('trialEndsAt');

                //active subscription
                $current = $subscription->get('subscriptionPlan');//ie subPlanActive
                $subscriptionTerm = $subscription->get('subscriptionTerm');
                $subscriptionTrial = $subscription->get('subscriptionTrial');

                //onGracePeriod of cancelled subscription
                $subPlanGrace= $subscription->get('subPlanGrace');
                $subTermGrace = $subscription->get('subTermGrace');
                $subTrialGrace= $subscription->get('subTrialGrace');

                //cancelled nonGracePeriod subscription
//                $subPlanCancel= $subscription->get('subPlanCancel');
                $subTermCancel= $subscription->get('subTermCancel');
                $subTrialCancel= $subscription->get('subTrialCancel');

                $numUsers = null;

                //convert planNum into numUsers
                if(isset($current)) {

                    $numUsers = planNumUsers($current);

                }else if(isset($subPlanGrace)){

                    $numUsers = planNumUsers($subPlanGrace);

                }

                //on subscription
                return view('company-settings.index')->with(array(
                    //company and users tab
                    'users' => $users,
                    'compInfo' => $compInfo,
                    'url' => $url,

                    'currentUser' => $currentUser,
                    'email' => $email,

                    //subscription tab, all subscription statuses ie cancelled, inGracePeriod, active
                    'numUsers' => $numUsers,

                    //subscription tab, if subscription
                    'current' => $current,
                    'subscriptionTerm' => $subscriptionTerm,
                    'subscriptionTrial' => $subscriptionTrial,

                    //subscription tab, if onGracePeriod of cancelled subscription
                    'subTermGrace' => $subTermGrace,
//                    'subPlanGrace' => $subPlanGrace,
                    'subTrialGrace' => $subTrialGrace,

                    //subscription tab, if cancelled subscription not on grace period
//                    'subPlanCancel' => $subPlanCancel,
                    'subTermCancel' => $subTermCancel,
                    'subTrialCancel' => $subTrialCancel,

                    //subscription tab, if no subscription
                    'trial' => $inTrial,
                    'trialEndsAt' => $trialEndsAt,
                    'inTrialJS' => null,

                ));//array

            } //user does not have a token
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

            return Redirect::to('/')->withErrors('Session has expired. Kindly login again.');

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
                return Redirect::to('/')->withErrors('Session has expired. Kindly login again.');
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

    /**returns the Subscription Pricing Model page for logged in users***/
    public function upgrade(){
        try {
            if (session()->has('token')) {

                //retrieve id from session data
                $id = session('id');

                $user = getUser($id);

                $email = $user->email;

                //todo: get the current subscription, if any, via the api, or perhaps display end trial date again on upgrade page??
                $current = null;//fixme

                $subscription = getSubscription();

                $inTrial = $subscription->get('inTrial');
                $trialEndsAt = $subscription->get('trialEndsAt');

                //active sub
                $current = $subscription->get('subscriptionPlan');
                $subscriptionTerm = $subscription->get('subscriptionTerm');
                $subscriptionTrial = $subscription->get('subscriptionTrial');

                //onGracePeriod of cancelled subscription
                $subPlanGrace= $subscription->get('subPlanGrace');
                $subTermGrace = $subscription->get('subTermGrace');
                $subTrialGrace= $subscription->get('subTrialGrace');

                //cancelled nonGracePeriod subscription
//                $subPlanCancel= $subscription->get('subPlanCancel');
                $subTermCancel= $subscription->get('subTermCancel');
                $subTrialCancel= $subscription->get('subTrialCancel');

                return view('company-settings/upgrade')->with(array(
                    'email' => $email,//users email to be used in checkout widget
                    'selected' => null,//if selected a plan already via pricing model on www.odincasemanagement.com or www.odinmgmt.azurewebsites.net/upgrade
                    'chosenTerm' => null,//current term
                    'public' => null,//used on upgrade_public but required on all views

                    //in trial, subscription not yet started
                    'inTrial' => $inTrial,
                    'trialEndsAt' => $trialEndsAt,
                    'inTrialJS' => null,

                    //active sub
                    'current' => $current,//plan num
                    'subscriptionTrial' => $subscriptionTrial,//must be sent to view if $current != null
                    'subscriptionTerm' => $subscriptionTerm,

                    //subscription tab, if onGracePeriod of cancelled subscription
                    'subTermGrace' => $subTermGrace,
                    'subPlanGrace' => $subPlanGrace,
                    'subTrialGrace' => $subTrialGrace,

                    //subscription tab, if cancelled subscription not on grace period
//                    'subPlanCancel' => $subPlanCancel,
                    'subTermCancel' => $subTermCancel,
                    'subTrialCancel' => $subTrialCancel,

                ));

                /*Upgrade Plan Pricing Model
                MSGS
                 * ATM: If on current plan ie $current !== "", swap plan , if $subscriptionTrial isset, show alert billing commences on trial date:
                 * need:
                 *
                 * BTNS
                 *  ATM: @if(isset($current)) swapBtn()
                 * ATM: @if(!isset($current)) && @if($inTrial === false) submitBtn with a payment amount
                 * ATM: @if(!isset($current))&& @if($inTrial === true) submitBtn with $0
                 * NEED BTNS:
                 * @if(isset($onGracePeriod)) resumeBtn()
                 * @if(isset($cancelled)) submitBtn with a payment amount
                 *
                 * */

            }//user does not have a token
            else {
                return Redirect::to('/login');
            }

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
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

    //have plan, period, email, stripeToken
    //if cancelled,notGracePeriod, we'll come via here with amount = amount.
        //msg to user once processed: $confirm ie receipt
        //current should show by using via $current variable
    //if onGracePeriod, we'll be resuming,
    //if on plan, we'll be swapping, perhaps come via here with amount = $0,
    //if not on trial anymore as trial ended, we'll be paying or maybe trial again for a short period?? todo: nigel
    //if on trial, come via here with amount = $0
        //msg to user once processed: $confirm ie not billed yet.
        //current should show by using via $current variable

    public function paymentUpgrade(Request $request){
        try {
            if (session()->has('token')) {

                //todo: ??get plan chosen
                $plan = $request->plan;
                $period = $request->period;
                $stripeToken = $request->stripeToken;
                $trialEndsAt = $request->trialEndsAt;
                $formPath = $request->formPath;

                $result = null;

                //create subscription
                if(isset($stripeToken)) {

                    //returns either success = false and primaryContact = false
                    //or returns success = false only if subscription did not update for some reason
                    //or returns success = true if successful created subscription
                    $result = postSubscription($plan, $stripeToken, $period, $trialEndsAt);

                } else{
                    if($formPath == "swap"){

                            $result = postSwapSubscription($plan, $period);
                    }
                }

                //use stripeEmail returned from payment request
                $email = $request->stripeEmail;

                $subscription = getSubscription();

                /*if form submission successful,
                the current & subscription term values will hold a value*/

                $current = $subscription->get('subscriptionPlan');
                $subscriptionTerm = $subscription->get('subscriptionTerm');
                $subscriptionTrial = $subscription->get('subscriptionTrial');

                /***if form submission fails the trial,
                 * cancel and grace period variables might hold a value,
                 * else value will be null***/

                $inTrial = $subscription->get('inTrial');
                $trialEndsAt = $subscription->get('trialEndsAt');

                //onGracePeriod of cancelled subscription
                $subPlanGrace= $subscription->get('subPlanGrace');
                $subTermGrace = $subscription->get('subTermGrace');
                $subTrialGrace= $subscription->get('subTrialGrace');

                //cancelled nonGracePeriod subscription
//                $subPlanCancel= $subscription->get('subPlanCancel');
                $subTermCancel= $subscription->get('subTermCancel');
                $subTrialCancel= $subscription->get('subTrialCancel');

                if($result->success == true) {

                    if($formPath == "swap") {

                        $confirm = 'SUCCESS! Plan upgraded. You have not been billed for this upgrade.';

                    }else {
                        if (isset($trialEndsAt)) {
                            $confirm = 'SUCCESS! Plan upgraded. You have not been billed for this upgrade.';

                        } else {

                            $confirm = 'SUCCESS! Plan upgraded. Receipt for the payment has been emailed to ' . $email;
                        }
                    }

                    return view('company-settings/upgrade')
                        ->with(array(
                            'email' => $email,
                            'confirm' => $confirm,
                            'public' => null,

                            'selected' => null,
                            'chosenTerm' => $subscriptionTerm,

                            'current' => $current,//todo: testing only atm
                            'subscriptionTrial' => $subscriptionTrial,//must be sent to view if $current != null
                            'subscriptionTerm' => $subscriptionTerm,

                            //if form submission fails these variables might hold a value, else value will be null
                            'inTrial' => $inTrial,
                            'trialEndsAt' => $trialEndsAt,//must be sent to view if($inTrial === true)
                            'inTrialJS' => null,

                            //subscription tab, onGracePeriod of cancelled subscription
                            'subTermGrace' => $subTermGrace,
                            'subPlanGrace' => $subPlanGrace,
                            'subTrialGrace' => $subTrialGrace,

                            //subscription tab, if cancelled subscription not on grace period
                            'subTermCancel' => $subTermCancel,
                            'subTrialCancel' => $subTrialCancel,
                        ));

                }else{

                    if(isset($result->subscriptionStatus)){
                        $msg = 'FAILED to update subscription. The status of your subscription is '.$result->subscriptionStatus.'. 
                        Please contact support to assist you in creating a subscription.';

                        return Redirect::to('/subscription/upgrade')->withErrors($msg);
                    }

                    //success is false
                    if(isset($result->primaryContact)){
                        //user is not the primar contact
                        $msg = 'FAILED to update subscription. Only the primary contact is authorized to manage subscriptions. 
                        The primary contact can be changed in settings>users';

                        return Redirect::to('/subscription/upgrade')->withErrors($msg);
                    }

                    //subscription failed for some unknown reason
                    $msg = 'FAILED to update subscription.  Please contact support to assist you in creating a subscription.';

                    return Redirect::to('/subscription/upgrade')->withErrors($msg);
                }

            }//user does not have a token
            else {

                $plan = $request->plan;
                $period = $request->period;

                if($plan != null)
                    return app('App\Http\Controllers\HomeController')->getIndex($plan, $period);

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

    //return the view "upgrade_layout.blade.php" with the checkout widget in the foreground (so initiate the btn click)
    public function upgradePlan($plan, $term){
        try {
            if (session()->has('token')) {

                //retrieve id from session data
                $id = session('id');

                $user = getUser($id);

                $email = $user->email;

                $subscription = getSubscription();

                //in trial
                $inTrial = $subscription->get('inTrial');
                $trialEndsAt = $subscription->get('trialEndsAt');

                //active sub
                $current = $subscription->get('subscriptionPlan');
                $subscriptionTerm = $subscription->get('subscriptionTerm');
                $subscriptionTrial = $subscription->get('subscriptionTrial');

                //onGracePeriod of cancelled subscription
                $subPlanGrace= $subscription->get('subPlanGrace');
                $subTermGrace = $subscription->get('subTermGrace');
                $subTrialGrace= $subscription->get('subTrialGrace');

                //cancelled nonGracePeriod subscription
//                $subPlanCancel= $subscription->get('subPlanCancel');
                $subTermCancel= $subscription->get('subTermCancel');
                $subTrialCancel= $subscription->get('subTrialCancel');

                //for current plans or onGracePeriod plans...
                $numUsers = null;

                //convert planNum into numUsers
                if(isset($current)) {

                    $numUsers = planNumUsers($current);

                }else if(isset($subPlanGrace)){

                    $numUsers = planNumUsers($subPlanGrace);

                }

                //for chosen plans from pricing model
                $newNumUsers = planNumUsers($plan);

                //convert the value $inTrial from true/false (boolean) to "true" "false" (strings)
                // because php conversion of false to js is not dealt with as expected on the view

                $inTrialJS = null;

                if(isset($inTrial)) {
                    if ($inTrial == false) {

                        $inTrialJS = "false";

                    } else {

                        $inTrialJS = "true";
                    }
                }

                //end remove soon

                return view('company-settings/upgrade')
                    ->with(array(
                    'email'=> $email,
                    'selected' => $plan,
                    'chosenTerm' => $term,
                    'newNumUsers' => $newNumUsers,

                    'public' => null,

                    //active subscription
                    'subscriptionTrial' => $subscriptionTrial,//must be sent to view if $current != null
                    'subscriptionTerm' => $subscriptionTerm,
                    'current' => $current,
                    'numUsers' => $numUsers,

                    //in trial
                    'inTrial' => $inTrial,
                    'trialEndsAt' => $trialEndsAt,//must be sent to view if($inTrial === true)
                    'inTrialJS' => $inTrialJS,

                    //subscription tab, if onGracePeriod of cancelled subscription
                    'subTermGrace' => $subTermGrace,
                    'subPlanGrace' => $subPlanGrace,
                    'subTrialGrace' => $subTrialGrace,

                    //subscription tab, if cancelled subscription not on grace period
//                    'subPlanCancel' => $subPlanCancel,
                    'subTermCancel' => $subTermCancel,
                    'subTrialCancel' => $subTrialCancel,
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

    public function editPrimaryContact(Request $request){

        try {
            if(session()->has('token')) {

            //optional values (scope to use the function elsewhere)
//                if($request->has('stripeEditToken')) {
                $stripeToken = $request->stripeEditToken;
//                }

//                if($request->has('stripeEditEmail')) {
                $stripeEmail = $request->stripeEditEmail;
//                }

                //required field
                $newPrimaryContact = $request->newPrimaryContact;

                //api request to update the credit card details
                dd("stripeToken", $stripeToken, '$newPrimaryContact', $newPrimaryContact, '$stripeEmail', $stripeEmail);

                //step 1. edit primary contact as create and cancel subscription check to make sure the user is the primary contact

                $responseBodyPrimary = putPrimaryContact($newPrimaryContact);//testing only, bring back in

                if($responseBodyPrimary->success == true) {
                        //for active subscriptions, cancel the current subscription and make a new subscription with new primary contact
                        if(isset($stripeToken)) {

                            //data needed for api requests
                            $subscription = getSubscription();

                            //old subscription
                            $subscriptionPlan = $subscription->get('subscriptionPlan');//ie subPlanActive
                            $subscriptionTerm = $subscription->get('subscriptionTerm');
                            $oldActiveSub = $subscription->get('activeSub');
                            $subscriptionId = $oldActiveSub->id;//fixme: field correct?

                            $primaryContact = $oldActiveSub->user_id;//fixme: field correct?

//                            //api request to update the credit card details
//                    dd("stripeToken", $stripeToken, '$newPrimaryContact', $newPrimaryContact, '$stripeEmail', $stripeEmail,
//                        '$subscriptionId', $subscriptionId, '$primaryCotnact', $primaryContact);


                            //step 2. cancel the current subscription, need returned to us:
                            $responseBody = cancelSubscription($subscriptionId);

                            if ($responseBody->success == true) {
                                if ($responseBody->result == "on grace period") {

                                    //onGracePeriod
                                    $endsAt = $responseBody->endsAt;//currently in the format 2018-06-05 02:42:27

                                    //for the create subscription, the $endsAt needs to be in the format = 5th June 2018
                                    $trialEndsAt = formatDates($endsAt);

                                } else {
                                    //cancelled effective now
                                    $trialEndsAt = null;
                                }

                                //step 3. create new subscription with cancelled subscription details
                                $responseBodySubscribe = postSubscription($subscriptionPlan, $stripeToken, $subscriptionTerm, $trialEndsAt);

                                if ($responseBodySubscribe->success == true) {
                                    //update the session primary contact
                                    session(['primaryContact' => $responseBodyPrimary->newPrimaryContact]);
                                    //success msg to user
                                    return redirect('/user')->with('status', 'Primary Contact Updated!');

                                } else {
                                    //create subscription failed

                                    //change the primary contact back to the original and advise the user the attempt failed. Provide a meaningful error msg if can.
                                    $responseBodyPrimary = putPrimaryContact($oldActiveSub->user_id);//todo,check, it is correct is it not. or else return the old primary contact when create a rpimar cotnatc.t

                                    if ($responseBodyPrimary->success == true) {

                                        //the change primary contact failed at the postSubscription point,
                                        // so we reverted to original primary contact and provide an error code
                                        $epcns = Config::get('constants.EDIT_PRIMARY_CONTACT_NEW_SUBSCRIPTION');

                                        return Redirect::back()
                                            ->withErrors('Failed to change the primary contact. 
                                        Please contact support for further assistance and advise them the error code is: ' . $epcns);

                                    } else {

                                        //we failed to revert back so the change is in place but the subscription has not swapped to the new primary contact.
                                        //therefore, todo: notify ourselves of the error and advise user that the process didn't complete smoothly.
                                        //try todo: and just change the credit card details for the user as an attempt before notifying ourselves and advising the user of the error.
                                        return Redirect::back()
                                            ->withErrors('Failed to successfully update all details associated with the primary contact. 
                                        The company subscription did not successfully update to include the new credit card details. 
                                        Please update credit card details via the subscriptions tab.');
                                    }
                                }

                            } else {
                                //cancel subscription failed

                                //atm, result on a false success will == "unauthorized", but scope for other error msgs as required/encountered
                                if ($responseBody->result == "unauthorized") {

                                    //change the primary contact back to the original and advise the user the attempt failed. Provide a meaningful error msg if can.
                                    $responseBodyPrimary = putPrimaryContact($oldActiveSub->user_id);//todo,check, it is correct is it not. or else return the old primary contact when create a rpimar cotnatc.t

                                    if ($responseBodyPrimary->success == true) {

                                        $err = 'User is not authorized to cancel the subscription on behalf of the company.';

                                        return Redirect::back()->withErrors($err);

                                    } else {

                                        //todo: notify ourselves

                                        $epcns = Config::get('constants.EDIT_PRIMARY_CONTACT');

                                        return Redirect::back()
                                            ->withErrors('Failed to update the primary contact. 
                                        Please contact support for further assistance and advise them the error code is: ' . $epcns);

                                    }
                                }

                            }
                        }

                        //for companies that do not have a subscription active at the moment,
                        //update the session primary contact
                        session(['primaryContact' => $responseBodyPrimary->newPrimaryContact]);
                        return redirect('/user')->with('status', 'Primary Contact Updated!');


                }else {
                    //success of update primary contact == false
                    $err = 'Failed to update the primary contact.';

                    return Redirect::back()
                        ->withErrors($err);
                }
            //end if(session()->has('token'))
            }else {
                return Redirect::to('/')->withErrors('Session has expired. Kindly login again.');

            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {

            dd($e);
            $err = 'Error communicating with server.';

            return Redirect::back()
                ->withErrors($err);

        } catch (\ErrorException $error) {
            dd($error);

            $err = 'Error sending through primary contact details.';

            return Redirect::back()
                ->withErrors($err);

        } catch (\Exception $err) {
            dd($err);

            $error = 'Error updating primary contact.';

            return Redirect::back()
                ->withErrors($error);

        } catch (\TokenMismatchException $mismatch) {
            $err = 'Session has expired. Kindly login again.';
            $errors = collect($err);
            return Redirect::to('/')->with('errors', $errors);
//            return Redirect::to('/')->withErrors('Session has expired. Kindly login again.');

        } catch (\InvalidArgumentException $invalid) {
            dd($invalid);

            $err = 'Error exception editing primary contact.';

            return Redirect::back()
                ->withErrors($err);

        } catch(\handleViewException $handle){
            dd($handle);

            $err = 'Error editing primary contact details.';

            return Redirect::back()
                ->withErrors($err);

        }
    }

    public function updateCreditCard(Request $request){
        try {
            if (session()->has('token')) {

                $stripeToken = $request->stripeCardToken;

                //api request to update the credit card details
                dd($stripeToken);


            }else{
                return Redirect::to('/')->withErrors('Session has expired. Kindly login again.');

            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error sending credit card details to the server.';

            return Redirect::back()
                ->withErrors($err);

        } catch (\ErrorException $error) {
            $err = 'Error updating credit card details.';

            return Redirect::back()
                ->withErrors($err);

        } catch (\Exception $err) {
            $error = 'Error updating credit card.';

            return Redirect::back()
                ->withErrors($error);

        } catch (\TokenMismatchException $mismatch) {

            return Redirect::to('/')->withErrors('Session has expired. Kindly login again.');

        } catch (\InvalidArgumentException $invalid) {
            $err = 'Error exception updating credit card details.';

            return Redirect::back()
                ->withErrors($err);

        } catch(\handleViewException $handle){
            $err = 'Error updating credit card details on the server.';

            return Redirect::back()
                ->withErrors($err);

        }
    }

    public function test(){

        $signature = '%2FyRVzgyQeGB8x0N6ZruXbWFla3KrP3l3%2BV3TcHG%2BRU8%3D';

        $signature = rawurlencode($signature);

        $myUrl = 'https://odinlitestorage.blob.core.windows.net/images/1518068557291.jpeg?st=2018-03-20T23%3A59%3A00Z&se=2018-03-22T08%3A00%3A00Z&sr=b&sp=r&sv=2017-04-17&rsct=image/jpeg&sig=';

        return view('home.test')->with(array(
                    'signature' => $signature,
                    'myUrl' => $myUrl,

                ));

        dd($signature);

        $myUrl = 'https://odinlitestorage.blob.core.windows.net/images/1518068557291.jpeg?st=2018-03-20T23%3A59%3A00Z&se=2018-03-22T08%3A00%3A00Z&sr=b&sp=r&sv=2017-04-17&rsct=image/jpeg&sig=';
    }

}

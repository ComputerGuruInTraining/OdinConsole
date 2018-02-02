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

                $users = array_sort($users, 'last_name', SORT_ASC);

                $url = 'user';

                return view('company-settings.index')->with(array('users' => $users, 'compInfo' => $compInfo, 'url' => $url));

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
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

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
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

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
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
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
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

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
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');
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

                if($user->success == true)
                {
                    $sessionId = session('id');

                    if($id == $sessionId){
                        return Redirect::to('/logout');
                    }else {
                        $msg = 'You have successfully deleted the user';
                        //display confirmation page
                        return view('confirm')->with('theAction', $msg);
                    }
                }
                else {
                    return Redirect::to('company/settings')->withErrors('Failed to delete user');
                }
            }
            //user does not have a valid token
            else {
                return Redirect::to('/login');
            }
        }//db error
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            return Redirect::to('/company/settings')->withErrors('Error deleting user');
        }//error returned to laravel and caught
        catch (\ErrorException $error) {
            return Redirect::to('/company/settings')->withErrors('Error deleting the user');
        } catch (\Exception $err) {
            return Redirect::to('/company/settings')->withErrors('Error deleting user from database');

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            return Redirect::to('/company/settings')->withErrors('Error deleting user from system');
        }
	}

    /*
     *
     * Register Page
     *
    */

    public function registerCompany()
    {
        return view('home.register');
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
                'last' => 'required|max:255'
            ]);

            $company = $request->input('company');
            $owner = $request->input('owner');
            $first = $request->input('first');
            $last = $request->input('last');
            $emailUser = $request->input('emailUser');
            $pw = $request->input('password');

            $client = new GuzzleHttp\Client;

            $url = Config::get('constants.STANDARD_URL');

            $response = $client->post($url.'company', array(
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array('company' => $company, 'owner' => $owner,
                        'first_name' => $first, 'last_name' => $last, 'email_user' => $emailUser, 'pw' => $pw
                    )
                )
            );

            $company = json_decode((string)$response->getBody());

            if ($company->success == true) {

                $msgLine1 = 'The company account has been created and an email has been sent to ' . $emailUser . ' to 
                complete the registration process.';

                $msgLine2 =  'The Odin Team welcomes you on board and we trust that you will enjoy the experience our app provides.';

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
            return Redirect::to('/register')
                ->withInput()
                ->withErrors('There is an error in the input. 
            This could be caused by an invalid email or 
            an email that already exists in the system. Please check your input.');
        } catch (\ErrorException $error) {
            return Redirect::to('/register')
                ->withInput()
                ->withErrors('Unable to complete the request. 
                Please check you have provided all required input as this may be the cause.');
        }catch (\InvalidArgumentException $err) {
            return Redirect::to('/register')
                ->withInput()
                ->withErrors('Unable to complete the request. Please check input is valid as this may 
                 be the cause.');
        }
    }

}

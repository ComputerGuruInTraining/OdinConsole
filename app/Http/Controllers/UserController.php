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

                $response = $client->get('http://odinlite.com/public/api/user/list/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $users = json_decode((string)$response->getBody());

                $resp = $client->get('http://odinlite.com/public/api/company/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $compInfo = json_decode((string)$resp->getBody());

//                dd($compInfo->company->id);

                $users = array_sort($users, 'last_name', SORT_ASC);


                return view('company-settings.index', compact('users', 'compInfo'));

            }
            //user does not have a token
            else {
                return Redirect::to('/login');
            }
        }
        //api error
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            return view('company-settings/index');
        }
        catch (\ErrorException $error) {
            return Redirect::to('/login');
        }
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create()
	{
        if (session()->has('token')) {
            return view('user/create');
        }
        else {
            return Redirect::to('/login');
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
                    'email' => 'required|email|max:255'
                ]);

                //data to be inserted into db
                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                $email = Input::get('email');
                $password = Hash::make(Input::get('password'));

                //api request variables
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $response = $client->post('http://odinlite.com/public/api/user', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'email' => $email, 'password' => $password, 'company_id' => $compId
                        )
                    )
                );
                $reply = json_decode((string)$response->getBody());
              //  dd($reply);
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
            $err = 'Error creating user. Please ensure email is valid.';
            $errors = collect($err);
            return view('user/create')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            $e = 'Please fill in all required fields with valid input';
            $errors = collect($e);
            return view('user/create')->with('errors', $errors);
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


                $response = $client->get('http://odinlite.com/public/api/user/' . $id . '/edit', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $user = json_decode((string)$response->getBody());

                return view('user/edit')->with('user', $user);

            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying page';
            $errors = collect($err);
            return view('company-settings/index')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            $e = 'Error displaying page';
            $errors = collect($e);
            return view('company-settings/index')->with('errors', $errors);
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
                    'email' => 'required|email|max:255'
                ]);

                $token = session('token');

                //get the data from the form
                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
                $email = Input::get('email');


                $client = new GuzzleHttp\Client;

                $response = $client->put('http://odinlite.com/public/api/user/'.$id.'/edit', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        ),
                        'json' => array('first_name' => $first_name, 'last_name' => $last_name,
                            'email' => $email
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
            return Redirect::to('company/settings')->withErrors('Please provide valid changes and ensure email is unique.');
        }
        catch (\ErrorException $error) {
            return Redirect::to('company/settings')->withErrors('Please provide valid changes and ensure the email is unique.');
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

                $response = $client->delete('http://odinlite.com/public/api/user/'.$id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $user = json_decode((string)$response->getBody());

                if($user->success == true)
                {
                    $msg = 'You have successfully deleted the user';
                    //display confirmation page
                    return view('confirm')->with('theAction', $msg);
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
                'password' => 'required|min:6|confirmed',
                'first' => 'required|max:255',
                'last' => 'required|max:255'
            ]);

            $company = $request->input('company');
            $owner = $request->input('owner');
            $first = $request->input('first');
            $last = $request->input('last');
            $emailUser = $request->input('emailUser');
            $pw = $request->input('password');
            $password = Hash::make($pw);

            $client = new GuzzleHttp\Client;

            $url = Config::get('constants.STANDARD_URL');

            $response = $client->post($url.'company', array(
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array('company' => $company, 'owner' => $owner,
                        'first_name' => $first, 'last_name' => $last, 'email_user' => $emailUser, 'pw' => $password
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
                return view('home.register');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'There is an error in the input. 
            This could be caused by an invalid email or an email that already exists in the system. Please check your input.';
            $errors = collect($err);
            return view('home.register')->with('errors', $errors);
        } catch (\ErrorException $error) {
            $e = 'Please fill in all required fields';
            $errors = collect($e);
            return view('home.register')->with('errors', $errors);

        }
    }

}

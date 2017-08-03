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

////use Illuminate\Support\Facades\View;
////use Illuminate\Routing\Controller;
////use Illuminate\Support\MessageBag;
////use Illuminate\Support\Facades\Auth;
///
//use Illuminate\Support\Facades\Redirect;


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
//                dd($users);
                return view('company-settings.index', compact('users'));

//                return view('user/index')->with(array('users' => $users, 'url' => 'user'));

            }
            else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
          //  echo $e;
            return view('company-settings/index');
        }
        catch (\ErrorException $error) {
            echo $error;
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
//		return View::make('user.create');

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
	public function store()
	{
        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
                $token = session('token');

                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                $first_name = Input::get('first_name');
                $last_name = Input::get('last_name');
               $email = Input::get('email');
               $password = Hash::make(Input::get('password'));

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
                $users = json_decode((string)$response->getBody());

                //display added users
                return Redirect::to('/user');

//                return view('confirm-create')->with(array('theData' => $name, 'url' => 'locations', 'entity' => 'Location'));
            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            //echo $e;
            $err = 'Error creating user.';
            $errors = collect($err);
            return view('user/create')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
            $e = 'Please fill in all required fields';
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
            echo $e;
            $err = 'Please provide a valid address and ensure the address is not already stored in the database.';
            $errors = collect($err);
            return view('user/create')->with('errors', $errors);
        }
        catch (\ErrorException $error) {
            //this catches for the instances where an address that cannot be converted to a geocode is input
            $e = 'Please fill in all required fields';
            $errors = collect($e);
            return view('user/create')->with('errors', $errors);
        }
//		$user = User::find($id);
//
//		return View::make('user.edit', [ 'user' => $user ]);
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update( $id)
	{

        try {
            if (session()->has('token')) {
                //retrieve token needed for authorized http requests
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
                    return redirect()->route('user.index');
                }
                else{
                    return redirect()->route("user.edit");
                }
            } else {
                return Redirect::to('/login');
            }
        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Please provide valid changes';
            $errors = collect($err);
            echo($err);
            return Redirect::to('/locations');
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

                return Redirect::to('/user');
            } else {
                return Redirect::to('/login');
            }
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            return Redirect::to('/user');
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

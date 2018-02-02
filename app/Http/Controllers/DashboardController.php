<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Dashboard;
use GuzzleHttp;
use Redirect;
use Config;


class DashboardController extends Controller
{

    public function index()
    {
        try {

            if (session()->has('token')) {
                $token = session('token');
                $client = new GuzzleHttp\Client;

                $compId = session('compId');

                //get current logged in console user to personalise dashboard
                $response = $client->get(Config::get('constants.API_URL') . 'user', array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $token,
                            'Content-Type' => 'application/json'
                        )
                    )
                );
                $users = json_decode((string)$response->getBody());

                $response2 = $client->get(Config::get('constants.API_URL') . 'dashboard/' . $compId . '/current-location', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $currentLocations = json_decode((string)$response2->getBody());

                $response3 = $client->get(Config::get('constants.API_URL') . 'location/' . $compId, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ]
                ]);

                $location = json_decode((string)$response3->getBody());

                //the $location is used for the center of the map
                //if there are no records returned for the company's mobile users current_location
                //however, if the company is new and has not yet added a location,
                //we need to use a default location

                //default =  San Francisco, CA, USA
                define('DEFAULT_LAT', 37.77493);
                define('DEFAULT_LONG', -122.419416);

                if (!isset($location->latitude)) {
                    $location->latitude = DEFAULT_LAT;
                    $location->longitude = DEFAULT_LONG;
                    $location->address = 'San Francisco, CA, USA';
                }

                $company = $this->getCompanyDetail();

                return view('dashboard.dashboard')->with(
                    array('users' => $users,
                        'currentLocations' => $currentLocations,
                        'company' => $company,
                        'center' => $location
                    ));

            } else {
                return Redirect::to('/login');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error displaying map';
            return view('error-msg')->with('msg', $err);

        } catch (\ErrorException $error) {
            $e = 'Error displaying GeoLocation map';
            return view('error-msg')->with('msg', $e);

        } catch (\Exception $err) {
            $e = 'Unable to display map';
            return view('error-msg')->with('msg', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login')
                ->withInput()
                ->withErrors('Session expired. Please login.');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error loading map';
            return view('error-msg')->with('msg', $error);
        }
    }

    public function getCompanyDetail()
    {
        if (session()->has('token')) {
            $token = session('token');
            $client = new GuzzleHttp\Client;

            $compId = session('compId');
            $response = $client->get(Config::get('constants.API_URL') . 'dashboard/' . $compId . '/company-detail', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $company = json_decode((string)$response->getBody());
            return $company;
        } else {
            return Redirect::to('/login');
        }
    }

//    public function testFunction(Request $request){
//        $locations = Location::all('latitude', 'longitude', 'name');
//        return $locations;
//    }

    public function privacy()
    {
        return view('home.privacy');
    }

    public function support()
    {
        return view('layouts.tabs.master_tabs_public');
    }

}

?>
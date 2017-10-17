<?php

namespace App\Http\Controllers;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Dashboard;
use GuzzleHttp;
use Redirect;
use Config;



class DashboardController extends Controller{

    public function index(){

        if (session()->has('token')) {
            $token = session('token');
            $client = new GuzzleHttp\Client;

            $compId = session('compId');

            //get current logged in console user to personalise dashboard
            $response = $client->get(Config::get('constants.API_URL').'user', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    )
                )
            );
            $users = json_decode((string)$response->getBody());

            $response2 = $client->get(Config::get('constants.API_URL').'dashboard/' . $compId . '/current-location', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $currentLocations = json_decode((string)$response2->getBody());

            $response3 = $client->get(Config::get('constants.API_URL').'location/' . $compId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $location = json_decode((string)$response3->getBody());

//            dd($currentLocations);

            $company = $this->getCompanyDetail();

            return view('dashboard.dashboard')->with(
                array('users' => $users,
                'currentLocations' => $currentLocations,
                'company' => $company,
                'center' => $location
            ));

        }
        else {
            return Redirect::to('/login');
        }
    }

    public function getCompanyDetail(){
        if (session()->has('token')) {
            $token = session('token');
            $client = new GuzzleHttp\Client;

            $compId = session('compId');
            $response = $client->get(Config::get('constants.API_URL').'dashboard/' . $compId . '/company-detail', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $company = json_decode((string)$response->getBody());
            return $company;
        }
        else {
            return Redirect::to('/login');
        }
    }

    public function testFunction(Request $request){
        $locations = Location::all('latitude', 'longitude', 'name');
        return $locations;
    }

    public function privacy()
    {
        return view('home.privacy');
    }

    public function support()
    {
        return view('home.support');
    }

}
?>
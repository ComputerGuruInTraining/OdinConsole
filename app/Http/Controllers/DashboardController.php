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
//        $locations = Location::all();
//        return view('dashboard.dashboard');
////        return view ('dashboard.dashboard', compact('locations'));


        if (session()->has('token')) {
            $token = session('token');
            $client = new GuzzleHttp\Client;

            $compId = session('compId');
//            $compId = session('compId');
//
//
//
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
//            dd($currentLocations);
            $company = $this->getCompanyDetail();

//            $companyName = $company->name;
//            $companyOwner = $company->owner;
////            dd($currentLocations);
//            dd($companyOwner);


            return view('dashboard.dashboard')->with(
                array('users' => $users,
                'currentLocations' => $currentLocations,
                'company' => $company
            ));

//            return view('dashboard/dashboard')->with(array('currentLocations' => $currentLocations));
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
//        if ($request->isMethod('post')){
//            return response()->json(['response' => 'This is post method']);
//        }
//
//        return response()->json(['response' => 'This is get method']);
        $locations = Location::all('latitude', 'longitude', 'name');
        return $locations;

//        return response()->json(['response'=>'latitude', 'longitude', 'name']);
    }

}
?>
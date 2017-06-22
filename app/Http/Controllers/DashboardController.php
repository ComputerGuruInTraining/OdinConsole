<?php

namespace App\Http\Controllers;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Dashboard;

class DashboardController extends Controller{

    public function index(){
        $locations = Location::all();
        return view ('dashboard.dashboard', compact('locations'));
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
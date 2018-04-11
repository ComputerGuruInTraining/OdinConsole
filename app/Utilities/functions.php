<?php
/**
 * Created by PhpStorm.
 * User: Bernadette
 * Date: 23/04/2017
 * Time: 1:32 PM
 */

//global confirm delete fn
if (!function_exists('confirmDlt')) {
    function confirmDlt($id, $url)
    {
        try {

            return view('confirm-delete')->with(array('id' => $id, 'url' => $url));

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error deleting item';
            return view('error')->with('error', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting item';
            return view('error')->with('error', $e);

        } catch (\Exception $err) {
            $e = 'Error deleting item';
            return view('error')->with('error', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error deleting item';
            return view('error')->with('error', $error);
        }
    }
}

//global delete confirm fn for manually defined routes which enable header toggle buttons eg menu
if (!function_exists('confirmDel')) {
    function confirmDel($id, $url)
    {
        try {

            return view('confirm-del')->with(array('id' => $id, 'url' => $url));

        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error deleting item';
            return view('error')->with('error', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting item';
            return view('error')->with('error', $e);

        } catch (\Exception $err) {
            $e = 'Error deleting item';
            return view('error')->with('error', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error deleting item';
            return view('error')->with('error', $error);
        }

    }
}

if (!function_exists('confirmDltReportCaseNote')) {
    function confirmDltReportCaseNote($id, $urlCancel, $reportId)
    {
        try {
            return view('confirm-delete-report-case')
                ->with(array('urlCancel' => $urlCancel, 'id' => $id, 'reportId' => $reportId));


        }catch (GuzzleHttp\Exception\BadResponseException $e) {
            $err = 'Error deleting item';
            return view('error')->with('error', $err);

        } catch (\ErrorException $error) {
            $e = 'Error deleting item';
            return view('error')->with('error', $e);

        } catch (\Exception $err) {
            $e = 'Error deleting item';
            return view('error')->with('error', $e);

        } catch (\TokenMismatchException $mismatch) {
            return Redirect::to('login');

        } catch (\InvalidArgumentException $invalid) {
            $error = 'Error deleting item';
            return view('error')->with('error', $error);
        }

    }
}

//usage: when storing shift in db & when storing a new report
if (!function_exists('jobDateTime')) {
    function jobDateTime($date, $time)
    {
        $y = substr($date, 6, 4);
        $d = substr($date, 3, 2);
        $m = substr($date, 0, 2);

        $dtStr = $y . "-" . $m . "-" . $d . " " . $time . ':00';

        return $dtStr;
    }
}

//db friendly date
if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        $y = substr($date, 6, 4);
        $d = substr($date, 3, 2);
        $m = substr($date, 0, 2);

        $dtStr = $y . "-" . $m . "-" . $d;

        return $dtStr;
    }
}

if (!function_exists('stringTime')) {
    function stringTime($tm)
    {
        $time = $tm->format("g") . '.' . $tm->format("i") . ' ' . $tm->format("a");
        return $time;
    }
}

if (!function_exists('timeMidnight')) {
    function timeMidnight($time)
    {
        if ($time != null) {
            if ($time == '12.00 am') {
                $time = 'Midnight';
            }
        }
        return $time;
    }
}

//jobDuration in hours
if (!function_exists('jobDuration')) {
    function jobDuration($carbonStart, $carbonEnd)
    {
        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM / 60);//convert to hours
        return $lengthH;
    }
}

//parameter is in seconds, convert to minutes and hours if applicable
if (!function_exists('totalMinsInHours')) {
    function totalMinsInHours($time)
    {
            if ($time < 60) {
                return $time .' s';
            }

            $mins = $time/60;

            $hours = floor($mins / 60);

            $minutes = ($mins % 60);

            if($hours != 0){

                return sprintf('%d h %d m', $hours, $minutes);

            }else{
                return sprintf('%d m', $minutes);

            }

    }
}

//if (!function_exists('locationDuration')) {
//    function locationDuration($start, $end)
//    {
//        $carbonStart = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start);
//        $carbonEnd = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $end);
//        //calculate duration based on start date and time and end date and time
//        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
//        $lengthH = ($lengthM / 60);//convert to hours
//        $hours = floor($lengthH * 100) / 100;//hours to 2 decimal places
//        return $hours;
//    }
//}

//an individual check in time in minutes
if (!function_exists('locationCheckDuration')) {
    function locationCheckDuration($start, $end)
    {
        $carbonStart = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $carbonEnd = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $end);
        //calculate duration based on start date and time and end date and time
        $lengthS = $carbonStart->diffInSeconds($carbonEnd);//calculate in minutes
        return $lengthS;
    }
}

//global oauth fn
if (!function_exists('oauth2')) {

    function oauth2($email, $password)
    {
        try {
            $client = new GuzzleHttp\Client;

            $url = Config::get('constants.STANDARD_URL');
            $urlApi = Config::get('constants.API_URL');

            $response = $client->post($url . 'oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 14,
                    // The secret generated when you ran: php artisan passport:install
                    'client_secret' => '6L5c7iaGiuNbqJFsL7zrmYf0gJaqY1in9YBvEb49',
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ],
            ]);

            $auth = json_decode((string)$response->getBody());

            $responseStatus = $client->get($urlApi . 'session', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $auth->access_token,
                ]
            ]);

            //array datatype, even though only 1 object in the array
            $session = json_decode((string)$responseStatus->getBody());

            $userDetails = new stdClass();

            foreach($session as $user){

                $userDetails = $user[0];
            }

//            dd($userDetails, $userDetails->status);

            //company account has been created but has not been activated via email authentication
            if ($userDetails->status != "active") {
                return false;
            } else {
                //ensure the user is in the user_role table and therefore allowed access to the console
                if ($userDetails->role != null) {
                    $token = $auth->access_token;
                    $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                    //save the token in a session helper method along with the user id and name
                    //see https://laravel.com/docs/5.4/session#retrieving-data Section:The Global Session Helper
                    session([
                        'token' => $token,
                        'id' => $userDetails->userId,
                        'name' => $name,
                        'role' => $userDetails->role,
                        'compId' => $userDetails->compId,
                        'compName' => $userDetails->name,
                        'primaryContact' => $userDetails->primary_contact,
                        'trialEndsAt' => $userDetails->trial_ends_at
                        ]);

                    return true;
                } //else the user is not allowed access to the console
                else {
                    return false;
                }

            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            return false;
        }
    }
}

if (!function_exists('formatDates')) {
    function formatDates($date)
    {
        $dt = new DateTime($date);
        $fdate = $dt->format('jS F Y');
        return $fdate;
    }
}

if (!function_exists('formatDatesShort')) {
    function formatDatesShort($date)
    {
        $dt = new DateTime($date);
        $fdate = $dt->format('j M y');
        return $fdate;
    }
}

//calculate time and date based on geoCoords using Google API
//return the values to be used to calculate the date and time
if (!function_exists('timezone')) {
    function timezone($lat, $long, $t)
    {
        $dateForTS = date_create($t);
        $dateInTS = date_timestamp_get($dateForTS);

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        //find the timezone for each case note using google timezone api
        $result = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $lat . ',' . $long .
            '&timestamp=' . $dateInTS . '&key=AIzaSyBbSWmsBgv_YTUxYikKaLTQGf5r4n0o-9I',
        false, stream_context_create($arrContextOptions));

        $data = json_decode($result);

        $collection = collect(['dstOffset' => $data->dstOffset, 'rawOffset' => $data->rawOffset]);

        return $collection;

    }
}

//calculate time and date based on geoCoords using Google API
//return the $date and $time
if (!function_exists('timezoneDT')) {
    function timezoneDT($lat, $long, $t)
    {
        $dateForTS = date_create($t);
        $dateInTS = date_timestamp_get($dateForTS);

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        //find the timezone for each case note using google timezone api
        $result = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $lat . ',' . $long .
            '&timestamp=' . $dateInTS . '&key=AIzaSyBbSWmsBgv_YTUxYikKaLTQGf5r4n0o-9I', false, stream_context_create($arrContextOptions));

        $data = json_decode($result);

        $tsUsingResult = $dateInTS + $data->dstOffset + $data->rawOffset;

        //convert timestamp to a datetime string
        $date = date('m/d/Y', $tsUsingResult);

        $time = date('g.i a', $tsUsingResult);

        $collection = collect(['date' => $date, 'time' => $time]);

        return $collection;

    }
}

/*Code sourced from: https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
Margin For Error: (under +/-1% error margin).*/
if (!function_exists('distance')) {

    function distance($lat1, $lon1, $lat2, $lon2) {

        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return $km;
    }
}

if (!function_exists('geoRange')) {

    function geoRange($distance){

        //200m
        $goodRange = 0.2;
        $okRange = 0.5;

        if($distance <= $goodRange){

            $result = 'yes';

        }else if($distance <= $okRange){

            $result = 'ok';

        }else{

            $result = 'no';
        }

        return $result;
    }
}

if (!function_exists('substrImg')) {

    function substrImg($stringImg)
    {
        $substrImg1 = substr($stringImg, 9);

        //remove 5 characters from the end of the filepath (ie remove .jpeg)
        $substrImg = substr($substrImg1, 0, -6);

        return $substrImg;
    }
}

//get a list of employees
if (!function_exists('getEmployees')) {

    function getEmployees()
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $compId = session('compId');

            $response = $client->get(Config::get('constants.API_URL').'employees/list/' . $compId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $emps = json_decode((string)$response->getBody());

            return $emps;
        }
    }
}

//get a list of locations
if (!function_exists('getLocations')) {

    function getLocations()
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $compId = session('compId');

            $response = $client->get(Config::get('constants.API_URL') . 'locations/list/' . $compId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $locations = json_decode((string)$response->getBody());

            return $locations;

        }
    }
}

//get a list of users that are not already added as employees
if (!function_exists('getUsers')) {

    function getUsers()
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $compId = session('compId');

            $response = $client->get(Config::get('constants.API_URL').'user/add-emp/' . $compId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $users = json_decode((string)$response->getBody());

            return $users;

        }
    }
}

if (!function_exists('getUser')) {

    function getUser($id)
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $response = $client->get(Config::get('constants.API_URL').'user/'.$id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            $user = json_decode((string)$response->getBody());

            return $user;
        }
    }
}

if (!function_exists('getUserRole')) {

    function getUserRole($userId)
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $response = $client->get(Config::get('constants.API_URL').'/user/role/'.$userId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            //retrieves role as an array datatype with the role at index 0
            $userRole = json_decode((string)$response->getBody());

            return $userRole[0];
        }
    }
}

if (!function_exists('storeErrorLog')) {

    function storeErrorLog($event, $recipient, $subject = null)
    {
        $client = new GuzzleHttp\Client;

        $response = $client->post(Config::get('constants.STANDARD_URL') . 'error-logging', array(
                'headers' => array(
                    'Content-Type' => 'application/json'
                ),
                'json' => array(
                    'event' => $event,
                    'recipient' => $recipient,
                    'description' => $subject
                )
            )
        );

        $result = GuzzleHttp\json_decode((string)$response->getBody());

        return $result;
    }
}

//function to remove the first and last character of a string
if (!function_exists('stringRemove1stAndLast')) {

    function stringRemove1stAndLast($img)
    {
        //remove 1st character
        $img1stRemoved = substr($img, 1);
        //remove last character
        $img1stAndLastRemoved = substr($img1stRemoved, 0, -1);

        return $img1stAndLastRemoved;

    }
}

//function to remove the first and last character of a string
if (!function_exists('removeForwardSlash')) {

    function removeForwardSlash($img)
    {
        //find the double forward slash ie // in the $img string which always occurs after casenotes/ so the 10th index
        //and remove the 2nd forward slash
        $folder = substr($img, 0, 10);//= casenotes/
        $filename = substr($img, 11);//timestamp

        //concat the two
        $imgForwardSlashRemoved = $folder.$filename;

        return $imgForwardSlashRemoved;
    }
}

if (!function_exists('currentYear')) {

    function currentYear()
    {
        $year = date("Y");
        return $year;
    }
}

//format timestamp to be a user friendly date and time which accounts for the timezone
if (!function_exists('geoRangeDateTime')) {

    function geoRangeDateTime($collection, $location = null)
    {
        foreach ($collection as $i => $item) {
//TODO: consider adding an endnote to report advising of this info

            $no_data = '';

//          check ins
//         if there is a value for the check_in datetime (ie check_ins property) therefore there is a record
            //(because of check_ins datetime is a default value for the field)
            if ($item->check_ins != null) {

                //for instances where location object is passed through as 2nd pm
                if($location != null){
                    $locLat = $location->latitude;
                    $locLong = $location->longitude;

                }else{
                    //for instances where location data is a part of each collection object
                    $locLat = $item->latitude;
                    $locLong = $item->longitude;
                }

                $tzDT = viaTimezone($item->checkin_latitude,
                    $item->checkin_longitude,
                    $locLat,
                    $locLong, $item->check_ins);

                $collection[$i]->dateTzCheckIn = $tzDT->get('date');
                $collection[$i]->timeTzCheckIn = $tzDT->get('time');

            } else {

                //loop
                $collection[$i]->dateTzCheckIn = $no_data;
                $collection[$i]->timeTzCheckIn = $no_data;
            }

        }
        return $collection;
    }
}

if (!function_exists('withinRange')) {

    function withinRange($item, $location)
    {
        //if there is geoLocation data for the location check
        if (($item->checkin_latitude != "") && ($item->checkin_longitude != "")) {

            $distance = distance($item->checkin_latitude, $item->checkin_longitude,
                $location->latitude, $location->longitude);
//                    $distance = distance($item->checkin_latitude, $item->checkin_longitude, -35.381536, 149.058894);// testing

            $result = geoRange($distance);

            //return $result;//yes, ok, no

            $item->withinRange = $result;

            if ($item->withinRange == 'yes') {

                $item->geoImg = 'if_checkmark-g_86134';
            } elseif ($item->withinRange == 'ok') {
                $item->geoImg = 'if_checkmark-o_86136';
            } elseif ($item->withinRange == 'no') {
                $item->geoImg = 'if_cross_5233';
            }

            //for testing purposes only: 1

//                    $distance = distance($item->checkin_latitude, $item->checkin_longitude, -35.381536, 149.058894);// testing


        } else {
            //  $result = "no geoData";
            //for testing purposes only: 1
//                 $distance = distance($checks->location->latitude, $checks->location->longitude, $checks->location->latitude, $checks->location->longitude);//should return 0.0km
            $item->withinRange = "-";

            $item->geoImg = 'if_minus_216340';

            //for testing purposes only: 2

//                    $distance2 = distance(-35.381536, 149.058894, $checks->location->latitude, $checks->location->longitude);//should return 0.0km

            //
            //Latitude -35.381536
//                    longitude: 149.058894


//                    $checks->clientData[$i]->withinRange = $distance + " should equal 0 as same location";
//                    $checks->clientData[$i]->withinRange = $distance2 + " not gathered";

        }

        return $item;


    }
}

//parameter is a ??stdclass, is then converted to collection & location used for workings
//returns the data collection with values added for the check_out date and time computed based on the geoLocation timezone
if (!function_exists('checkOutDateTime')) {

    function checkOutDateTime($data, $location = null)
    {
        $no_data = '';

        foreach ($data as $i => $item) {

            if ($item->check_outs != null) {

                //for instances where location object is passed through as 2nd pm
                if($location != null){
                    $locLat = $location->latitude;
                    $locLong = $location->longitude;

                }else{
                    //for instances where location data is a part of each collection object
                    $locLat = $item->latitude;
                    $locLong = $item->longitude;
                }

                $tz = viaTimezone($item->checkout_latitude,
                    $item->checkout_longitude,
                    $locLat,
                    $locLong, $item->check_outs);

                $data[$i]->dateTzCheckOut = $tz->get('date');
                $data[$i]->timeTzCheckOut = $tz->get('time');

            } else {
                $data[$i]->dateTzCheckOut = $no_data;
                $data[$i]->timeTzCheckOut = $no_data;
            }
        }

        $collectChecks = collect($data);

        return $collectChecks;
    }
}

if (!function_exists('viaTimezone')) {

    function viaTimezone($geoLatitude, $geoLongitude, $locLatitude, $locLongitude, $dateTime)
    {
        //if there is geoLocation data for the location check
        if (($geoLatitude != "") && ($geoLongitude != "")) {

            $lat = $geoLatitude;
            $long = $geoLongitude;

        } else {
            //else use the location for the location check
            //TODO: consider adding an endnote to report advising of this info
            $lat = $locLatitude;
            $long = $locLongitude;
        }

        $tzDT = timezoneDT($lat, $long, $dateTime);

        return $tzDT;


    }
}

if (!function_exists('imgToUrl')) {

    function imgToUrl($item){

        if($item->title != "Nothing to Report") {

//for v2 case note uploads
            if (isset($item->img)) {
                if (($item->img != "") && ($item->img != null)) {

                    $item->hasImg = 'Y';

                    //remove the first and last character from the string ie remove " and " around string
                    $subImg = stringRemove1stAndLast($item->img);

                    //replace $item->img with formatted string
                    $item->img = $subImg;

                    //get from api the url for the img
                    $url = downloadImg($item->img);
                    $item->url = $url;
                }
//for v3 uploads
            } else if (isset($item->files)) {

                if ((count($item->files) > 0)) {

                    $item->hasImg = 'Y';

                    $imgs = [];
                    $urls = [];
                    $fullUrls = [];

                    for ($index = 0; $index < sizeof($item->files); $index++) {

                        //remove the first and last character from the string ie remove " and " around string
                        $imgs[$index] = stringRemove1stAndLast($item->files[$index]);

                        $urls[$index] = downloadImg('thumb'.$imgs[$index]);

                        $fullUrls[$index] = downloadImg($imgs[$index]);
                    }

                    $item->imgs = $imgs;

                    $item->urls = $urls;

                    $item->fullUrls = $fullUrls;

                } else {
                    $item->hasImg = '-';
                }
            } else { //no image
                $item->hasImg = '-';

            }
        }else { //no image
            $item->hasImg = '-';

        }

        return $item;
    }
}

if (!function_exists('downloadImg')) {

    function downloadImg($file)
    {
        //http request
        if (session()->has('token')) {

            $client = new GuzzleHttp\Client;

            //response is a url
            $response = $client->get(Config::get('constants.STANDARD_URL') . 'download-photo/' . $file, [
            ]);

            $url = json_decode((string)$response->getBody());//empty {} if file doesn't exist

            return $url;
        }

    }
}

//if text has more than 100 characters, retrieve the first 100 chars in a substring and add an elipsis to the end.
if (!function_exists('first100Chars')) {

    function first100Chars($text){

        if(strlen($text) > 100){

            $shortText = substr($text, 0, 100) . "...";

            return $shortText;

        }
    }
}

if (!function_exists('loadPdf')) {

    function loadPdf($viewName, $type){

        // pass view file
        $pdf = PDF::loadView($viewName)->setPaper('a4', 'landscape');
        // download pdf w current date in the name
        $dateTime = Carbon\Carbon::now();
        $date = substr($dateTime, 0, 10);

        return $pdf->download('Activity Report:'. $type . $date . '.pdf');
    }
}

if (!function_exists('nullifyDuplicates')) {
    function nullifyDuplicates($collection)
    {//all items will have uniqueShiftCheckId with a value, the items that are repeated for a shiftCheckId will have a value of null


        for ($z = 0; $z < count($collection); $z++) {

            $collection[$z]->uniqueShiftCheckId = $collection[$z]->shift_check_id;
        }

        for ($i = 0; $i < count($collection); $i++) {

            for ($j = 0; $j < count($collection); $j++) {

                //if startDate & shift time the same, preserve the startDate values for future comparisons and use:
                //and add null to the uniqueDate field which was assigned the values in the startDate field previously,
                if ($collection[$i]->shift_check_id == $collection[$j]->shift_check_id) {
                    if ($j > $i) {
                        $collection[$j]->uniqueShiftCheckId = null;
                    }
                }
            }
        }

        return $collection;
    }
}

if (!function_exists('casesToArray')) {
    function casesToArray($collection, $source = null)
    {
        for ($i = 0; $i < count($collection); $i++) {
            $casesArray = [];

            //just loop the $i that are not repeated shiftCheckIds
            if ($collection[$i]->uniqueShiftCheckId != null) {

                //add an array for the several case notes that have the same shiftCheckId
                //compare against the entire collection
                for ($j = 0; $j < count($collection); $j++) {
                    if ($collection[$i]->shift_check_id == $collection[$j]->shift_check_id) {

                        $object = new stdClass();
                        $object->case_id = $collection[$j]->case_id;
                        $object->title = $collection[$j]->title;

                        if ($source == "locationReport") {
                            $object->description = $collection[$j]->description;
                            $object->case_notes_deleted_at = $collection[$j]->case_notes_deleted_at;
                            $object->hasImg = $collection[$j]->hasImg;

                            if (isset($collection[$j]->shortDesc)) {
                                $object->shortDesc = $collection[$j]->shortDesc;
                            }
                        }else if($source == 'individualReport'){
                            $object->case_notes_deleted_at = $collection[$j]->deleted_at;

                        }

                        array_push($casesArray, $object);

                    }
                }
                $collection[$i]->cases = $casesArray;

            } else {
                //repeated shiftCheckIds will have an empty array
                $collection[$i]->cases = [];
            }
        }

        return $collection;
    }
}

if (!function_exists('caseNoteReported')) {
    function caseNoteReported($collection)
    {
        for ($c = 0; $c < count($collection); $c++) {

            //default is that a case note has not been reported or has been deleted
            $note = "Nothing to Report";

            //if there are more than 1 case notes for a check in ie repeated shiftCheckIds
            if(count($collection[$c]->cases) > 1) {

                //loop through the case notes
                for ($b = 0; $b < count($collection[$c]->cases); $b++) {

                    if($collection[$c]->cases[$b]->title != "Nothing to Report"){
                        //if there is a case note reported that has not been deleted, the $note = "Case Note Reported"
                        if($collection[$c]->cases[$b]->case_notes_deleted_at == null){
                            $note = "Case Note Reported";

                        }
                    }
                }
            }

            $collection[$c]->note = $note;
        }

        return $collection;
    }
}

if (!function_exists('verificationFailedMsg')) {

    function verificationFailedMsg()
    {
        $msg = 'Access to the record denied';
        return view('error-msg')->with(array(
            'msg' => $msg,
            'errorTitle' => 'Authentication Failed'
        ));
    }
}

if (!function_exists('refuseDeleteMsg')) {

    function refuseDeleteMsg($msg, $entity)
    {
        return view('error-msg')->with(array(
            'msg' => $msg,
            'errorTitle' => 'Request to delete '. $entity . ' denied'
        ));
    }
}

if (!function_exists('planSpecs')) {

    function planSpecs($plan, $term)
    {
        $amount = 0;
        $numUsers = "";

        if($plan == 'plan1'){

            $numUsers = "up to 5";

            if($term == 'monthly'){
                $amount = Config::get('constants.AMOUNT_M1');

            }else{
                //term == 'quarterly'
                $amount = Config::get('constants.AMOUNT_Y1');
            }

        }else if($plan == 'plan2'){

            $numUsers = "6 - 10";

            if($term == 'monthly'){
                $amount = Config::get('constants.AMOUNT_M2');

            }else{
                //term == 'quarterly'
                $amount = Config::get('constants.AMOUNT_Y2');
            }
        }else if($plan == 'plan3'){

            $numUsers = "11 - 20";

            if($term == 'monthly'){
                $amount = Config::get('constants.AMOUNT_M3');

            }else{
                //term == 'quarterly'
                $amount = Config::get('constants.AMOUNT_Y3');
            }
        }

        $collection = collect(['amount' => $amount, 'numUsers' => $numUsers]);

        return $collection;
    }
}


if (!function_exists('planNumUsers')) {

    function planNumUsers($plan)
    {
        $numUsers = "";

        if($plan == 'plan1'){

            $numUsers = "up to 5";

        }else if($plan == 'plan2'){

            $numUsers = "6 - 10";

        }else if($plan == 'plan3'){

            $numUsers = "11 - 20";
        }

        return $numUsers;
    }
}

//create subscription in db and with stripe service
//returns either success = false and primaryContact = false
//or returns success = false only if subscription did not update for some reason
//or returns success = true
if (!function_exists('postSubscription')) {

    function postSubscription($plan, $stripeToken, $period, $trialEndsAt)
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $response = $client->post(Config::get('constants.API_URL').'subscription/create', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array(
                        'plan' => $plan,
                        'stripeToken' => $stripeToken,
                        'term' => $period,
                        'trialEndsAt' => $trialEndsAt
                    )
                )
            );

            $result = json_decode((string)$response->getBody());

            return $result;

        }
    }
}

//swap subscription in the db and with stripe service
if (!function_exists('postSwapSubscription')) {

    function postSwapSubscription($plan, $period)
    {
        if (session()->has('token')) {
            $token = session('token');

            $client = new GuzzleHttp\Client;

            $response = $client->post(Config::get('constants.API_URL').'subscription/swap', array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ),
                    'json' => array(
                        'plan' => $plan,
                        'term' => $period,
                    )
                )
            );

            $result = json_decode((string)$response->getBody());

            return $result;

        }
    }
}
if (!function_exists('checkSwapOrSame')) {

    function checkSwapOrSame(){



    }

}


//returns 1 of 4 variable sets:
//1. return $subscriptions == 1 active subscription (will only be one based on design);
//2. or return , $graceSub == if in trialPeriod but cancelled (with the latest trial_ends_at date,
///////// as could be 2 if edit primary contact, and cancel first subscription, create 2nd, then user cancels 2nd;
//3. or return $cancelSub == if not in trial period but cancelled (with the latest trial_ends_at date, as with gracePeriod, could be 2.)
//4. or returns $trial == false or inTrial == true and $trial_ends_at == date if no subscription created yet ever.
if (!function_exists('getSubscription')) {

    function getSubscription()
    {
        if (session()->has('token')) {
            $token = session('token');
            $compId = session('compId');

            $client = new GuzzleHttp\Client;

            $response = $client->get(Config::get('constants.API_URL').'/subscription/'.$compId, array(
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    )
                )
            );

            $subscriptionStatus = json_decode((string)$response->getBody());

            //in trial, no subscription
            $inTrial = null;
            $trialEndsAt = null;

            //active subscription
            $subscriptionPlan = null;
            $subscriptionTerm = null;
            $activeSub = null;
            $subscriptionTrial = null;

            //inGracePeriod subscription variables
            $subPlanGrace = null;
            $subTrialGrace = null;
            $subTermGrace = null;
            $graceSub = null;

            //cancelled nonGracePeriod subscription
            $subTrialCancel = null;
            $subTermCancel = null;
            $subPlanCancel = null;
            $cancelSub = null;

            if(isset($subscriptionStatus->trial)) {

                //$subscriptionStatus->trial_ends_at is an object of stdclass
                //php manual advises will json_encode to a simple js object
                //the result here is a string in the json format
                $trialEndsAt = jsonDate($subscriptionStatus->trial_ends_at);

                if($subscriptionStatus->trial == true){
//                if (isset($subscriptionStatus->trial_ends_at)) {

                    $inTrial = true;

                }else{
                    $inTrial = false;
                }

            }else if(isset($subscriptionStatus->subscriptions)) {

                //active subscription, just the 1 object
                $activeSub = $subscriptionStatus->subscriptions;

                //stripe_plan holds the plan id from stripe plan which can be used to determine the plan and period
                $stripePlan = stripePlan($activeSub->stripe_plan);

                $subscriptionPlan = $stripePlan->get('planNum');

                $subscriptionTerm = $stripePlan->get('term');

                if (isset($activeSub->trial_ends_at))
                    $subscriptionTrial = formatDates($activeSub->trial_ends_at);

            }else if(isset($subscriptionStatus->graceSub)){

                //cancelled subscription, still in trial grace period, just the 1 object
                $graceSub = $subscriptionStatus->graceSub;

                //stripe_plan holds the plan id from stripe plan which can be used to determine the plan and period
                $stripePlanGrace = stripePlan($graceSub->stripe_plan);

                $subPlanGrace = $stripePlanGrace->get('planNum');

                $subTermGrace = $stripePlanGrace->get('term');

                if (isset($graceSub->trial_ends_at))
                    $subTrialGrace = formatDates($graceSub->trial_ends_at);

            }else if(isset($subscriptionStatus->cancelSub)){

                //cancelled subscription, not in trial period, just the 1 object
                $cancelSub = $subscriptionStatus->cancelSub;

                //stripe_plan holds the plan id from stripe plan which can be used to determine the plan and period
                $stripePlanCancel = stripePlan($cancelSub->stripe_plan);

                $subPlanCancel = $stripePlanCancel->get('planNum');

                $subTermCancel = $stripePlanCancel->get('term');

                if (isset($cancelSub->trial_ends_at))
                    $subTrialCancel = formatDates($cancelSub->trial_ends_at);

            }

            $collection = collect([
                //intrial, no subscription
                'inTrial' => $inTrial,
                'trialEndsAt' => $trialEndsAt,
                //active subscription
                'subscription' => $activeSub,//tentative remove as not used, but check other fn calls first
                'subscriptionPlan' => $subscriptionPlan,
                'subscriptionTerm' => $subscriptionTerm,
                'subscriptionTrial' => $subscriptionTrial,
                //inGracePeriod subscription
                'graceSub' => $graceSub,//tentative remove as not used, but check other fn calls first
                'subTermGrace' => $subTermGrace,
                'subPlanGrace' => $subPlanGrace,
                'subTrialGrace' => $subTrialGrace,
                //cancelled nonGracePeriod subscription
                'cancelSub' => $cancelSub,//tentative remove as not used, but check other fn calls first
                'subPlanCancel' => $subPlanCancel,//tentative remove as not used, but check other fn calls first
                'subTermCancel' => $subTermCancel,
                'subTrialCancel' => $subTrialCancel,//tentative remove as not used, but check other fn calls first
            ]);

            return $collection;
        }
    }
}

if (!function_exists('jsonDate')) {

    function jsonDate($jsonStringDate)
    {
        $date = json_encode($jsonStringDate);//$date = a json string

        $json = json_decode($date, true);

        $date = formatDates($json['date']);

        return $date;
    }
}

if (!function_exists('stripePlan')) {

    function stripePlan($planId)
    {
        $stripeKey = Config::get('services.stripe.key');
        $planNum = "";
        $term = "";

        if (strpos($stripeKey, 'test')) {

            if($planId == Config::get('constants.TEST_PLAN1_MONTHLY')){

                $planNum = 'plan1';
                $term = 'monthly';

            }else if($planId == Config::get('constants.TEST_PLAN1_YEARLY')){
                $planNum = 'plan1';
                $term = 'yearly';
            }
        }else{
            if($planId == Config::get('constants.PLAN1_MONTHLY')){

                $planNum = 'plan1';
                $term = 'monthly';

            }else if($planId == Config::get('constants.PLAN2_MONTHLY')){

                $planNum = 'plan2';
                $term = 'monthly';

            }else if($planId == Config::get('constants.PLAN3_MONTHLY')) {

                $planNum = 'plan3';
                $term = 'monthly';

            }else if($planId == Config::get('constants.PLAN4_MONTHLY')){

                $planNum = 'plan4';
                $term = 'monthly';

            }else if($planId == Config::get('constants.PLAN1_YEARLY')){

                $planNum = 'plan1';
                $term = 'yearly';

            }else if($planId == Config::get('constants.PLAN2_YEARLY')){

                $planNum = 'plan2';
                $term = 'yearly';

            }else if($planId == Config::get('constants.PLAN3_YEARLY')){

                $planNum = 'plan3';
                $term = 'yearly';

            }else if($planId == Config::get('constants.PLAN4_YEARLY')){

                $planNum = 'plan4';
                $term = 'yearly';
            }
        }

        $collection = collect(['planNum' => $planNum, 'term' => $term]);

        return $collection;

    }
}

//if (!function_exists('pullApartString')) {
//
//    function pullApartString($string, $separator)
//    {
//
//        $strpos = strpos($string, $separator);
//
////dd($strpos);
//        if($strpos != 0) {
//            //get the portion at the end of the original string
//            $string2 = substr($string, ($strpos+1));//term
//
//            //get the portion at the beginning of the original string
//            $string1 = substr($string, 0, ($strpos));//plan
//
////        dd($strpos, $string2, $string1);
//
//
//            $collection = collect(['string1' => $string1, 'string2' => $string2,]);
//
//            return $collection;
//
//        }else{
//
//            return null;
//        }
//    }
//}



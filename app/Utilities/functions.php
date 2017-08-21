<?php
/**
 * Created by PhpStorm.
 * User: Bernadette
 * Date: 23/04/2017
 * Time: 1:32 PM
 */

//global confirm delete fn
if(! function_exists('confirmDlt')){
    function confirmDlt($id, $url) {
        try{

            $msg = '';

            if($url == 'employees'){
                $msg = 'Consider this carefully because deleting an employee may affect other data in the database related to the employee.';
            }

            if($url == 'locations'){
                $msg = 'Consider this carefully because a shift may be assigned to the location and reports may be impacted.';
            }

            return view('confirm-delete')->with(array('id' => $id, 'url' => $url, 'msg' => $msg));

        } catch(\ErrorException $error){
//            echo $error;
            Redirect::to('/rosters');
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
//            echo $e;
            Redirect::to('/rosters');
        }
    }
}

//global delete confirm fn for manually defined routes which enable header toggle buttons eg menu
if(! function_exists('confirmDel')){
    function confirmDel($id, $url) {
        try{

            $msg = '';

            if($url == 'rosters'){
                $msg = 'Consider this carefully because, for eg, if a shift for a particular date is being deleted,
                    this will delete all the shift details for that date.';
            }

            if($url == 'employees'){
                $msg = 'Consider this carefully because deleting an employee may affect other data in the database related to the employee.';
            }

            if($url == 'locations'){
                $msg = 'Consider this carefully because a shift may be assigned to the location and reports may be impacted.';
            }

            return view('confirm-del')->with(array('id' => $id, 'url' => $url, 'msg' => $msg));

        } catch(\ErrorException $error){
//            echo $error;
            Redirect::to('/rosters');
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
//            echo $e;
            Redirect::to('/rosters');
        }
    }
}

//usage: when storing shift in db & when storing a new report
if(! function_exists('jobDateTime')) {
    function jobDateTime($date, $time)
    {
        $y = substr($date, 6, 4);
        $d = substr($date, 3, 2);
        $m = substr($date, 0, 2);

        $dtStr = $y . "-" . $m . "-" . $d . " " . $time . ':00';

        return $dtStr;
    }
}

if(! function_exists('dateFormat')) {
    function dateFormat($date)
    {
        $y = substr($date, 6, 4);
        $d = substr($date, 3, 2);
        $m = substr($date, 0, 2);

        $dtStr = $y . "-" . $m . "-" . $d;

        return $dtStr;
    }
}

if(! function_exists('stringTime')) {
    function stringTime($tm)
    {
        $time = $tm->format("g") . '.' . $tm->format("i") . ' ' . $tm->format("a");
        return $time;
    }
}

if(! function_exists('timeMidnight')) {
    function timeMidnight($time){
        if($time != null) {
            if($time == '12.00 am')
            {
                $time = 'Midnight';
            }
        }
        return $time;
    }
}

if(! function_exists('jobDuration')) {
    function jobDuration($carbonStart, $carbonEnd)
    {
        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM / 60);//convert to hours
        return $lengthH;
    }
}

//global oauth fn
if(! function_exists('oauth2')) {

    function oauth2($email, $password)
    {
        try {
            $client = new GuzzleHttp\Client;

            $response = $client->post('http://odinlite.com/public/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    // The secret generated when you ran: php artisan passport:install
                    'client_secret' => 'OLniZWzuDJ8GSEVacBlzQgS0SHvzAZf1pA1cfShZ',
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ],
            ]);

            $auth = json_decode((string)$response->getBody());

            //get user
            $responseUser = $client->get('http://odinlite.com/public/api/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $auth->access_token,
                ]
            ]);

            $user = json_decode((string)$responseUser->getBody());

            $responseStatus = $client->get('http://odinlite.com/public/api/status/' . $user->company_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $auth->access_token,
                ]
            ]);

            //array datatype, even though only 1 item in the array
            $status = json_decode((string)$responseStatus->getBody());
            //company account has been created but has not been activated via email authentication
            if ($status != "active") {
                return false;
            } else {
                $responseRole = $client->get('http://odinlite.com/public/api/user/role/' . $user->id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $auth->access_token,
                    ]
                ]);

                //array datatype, even though only 1 item in the array
                $role = json_decode((string)$responseRole->getBody());

                //ensure the user is in the user_role table and therefore allowed access to the console
                if ($role != null) {
                    $token = $auth->access_token;
                    $name = $user->first_name . ' ' . $user->last_name;
                    //save the token in a session helper method along with the user id and name
                    //see https://laravel.com/docs/5.4/session#retrieving-data Section:The Global Session Helper
                    session([
                        'token' => $token,
                        'id' => $user->id,
                        'name' => $name,
                        'role' => $role[0],
                        'compId' => $user->company_id]);

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

if(! function_exists('formatDates')) {
    function formatDates($date)
    {
        $dt = new DateTime($date);
        $fdate = $dt->format('jS F Y');
        return $fdate;
    }
}
//calculate time and date based on geoCoords using Google API
//return the values to be used to calculate the date and time
if(! function_exists('timezone')) {
    function timezone($lat, $long, $t)
    {
        $dateForTS = date_create($t);
        $dateInTS = date_timestamp_get($dateForTS);

        //find the timezone for each case note using google timezone api
        $result = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $lat . ',' . $long .
            '&timestamp=' . $dateInTS . '&key=AIzaSyBbSWmsBgv_YTUxYikKaLTQGf5r4n0o-9I');

        $data = json_decode($result);

        $collection = collect(['dstOffset' => $data->dstOffset, 'rawOffset' => $data->rawOffset]);

        return $collection;

    }
}
//calculate time and date based on geoCoords using Google API
//return the $date and $time
if(! function_exists('timezoneDT')) {
    function timezoneDT($lat, $long, $t)
    {
        $dateForTS = date_create($t);
        $dateInTS = date_timestamp_get($dateForTS);

        //find the timezone for each case note using google timezone api
        $result = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $lat . ',' . $long .
            '&timestamp=' . $dateInTS . '&key=AIzaSyBbSWmsBgv_YTUxYikKaLTQGf5r4n0o-9I');

        $data = json_decode($result);

        $tsUsingResult = $dateInTS + $data->dstOffset + $data->rawOffset;

        //convert timestamp to a datetime string
        $date = date('m/d/Y', $tsUsingResult);

        $time = date('g.i a', $tsUsingResult);

        return array($date, $time);

    }
}




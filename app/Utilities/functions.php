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

//            $msg = '';
//
//            if ($url == 'employees') {
//                $msg = 'Consider this carefully because deleting an employee may affect other data in the database related to the employee.';
//            }
//
//            if ($url == 'locations') {
//                $msg = 'Consider this carefully because a shift may be assigned to the location and reports may be impacted.';
//            }

//            if($url == 'case-notes'){
//
////                $url = redirect()->back();
////                $url = redirect()->back()->getTargetUrl();
////                $url = re('routeName', ['id' => 1], false);
////                $url = $request->path();
//
//
//            }

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

//            $msg = '';
//
//            if ($url == 'rosters') {
//                $msg = 'Consider this carefully because, for eg, if a shift for a particular date is being deleted,
//                    this will delete all the shift details for that date.';
//            }
//
//            if ($url == 'employees') {
//                $msg = 'Consider this carefully because deleting an employee may affect other data in the database related to the employee.';
//            }
//
//            if ($url == 'locations') {
//                $msg = 'Consider this carefully because a shift may be assigned to the location and reports may be impacted.';
//            }

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

if (!function_exists('jobDuration')) {
    function jobDuration($carbonStart, $carbonEnd)
    {
        //calculate duration based on start date and time and end date and time
        $lengthM = $carbonStart->diffInMinutes($carbonEnd);//calculate in minutes
        $lengthH = ($lengthM / 60);//convert to hours
        return $lengthH;
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

            //get user
            $responseUser = $client->get($urlApi . 'user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $auth->access_token,
                ]
            ]);

            $user = json_decode((string)$responseUser->getBody());

            $responseStatus = $client->get($urlApi . 'status/' . $user->company_id, [
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
                $responseRole = $client->get($urlApi . 'user/role/' . $user->id, [
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
            echo "<script>console.log( 'Error during login' );</script>";
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
//calculate time and date based on geoCoords using Google API
//return the values to be used to calculate the date and time
if (!function_exists('timezone')) {
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
if (!function_exists('timezoneDT')) {
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
//            define("OUR_GOOD_RANGE", 0.2);
//
//            define("OUR_OK_RANGE", 0.5);

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

//TODO: WIP
//if (!function_exists('navTo')) {
//    function navTo($id, $url){
//        if($id == 0){
//            return Redirect::to('/'.$url);
//
//        }else{
//            return Redirect::to('/'.$url.'-'.$id);
//        }
//    }
//}

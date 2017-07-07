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

            $msg = 'Consider this carefully because other records associated with this item may be affected.';

            if($url == 'rosters'){
                $msg = 'Consider this carefully because, for eg, if a shift for a particular date is being deleted,
                    this will delete all the shift details for that date.';
            }

            if($url == 'employees'){
                $msg = 'Consider this carefully because, for eg, if an employee is being deleted,
                     all shifts assigned to the employee will also be deleted etc.';
            }

            if($url == 'locations'){
                $msg = 'Consider this carefully because all shifts assigned to the location will also be deleted.';
            }

            return view('confirm-delete')->with(array('id' => $id, 'url' => $url, 'msg' => $msg));

        } catch(\ErrorException $error){
            echo $error;
            Redirect::to('/rosters');
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            Redirect::to('/rosters');
        }
    }
}

//pm format $date = dd/mm/yyyy, $time = HH:MM

/*
 * this returns a string value
 * with the format
 * required for the mysql database
 *  datetime format of yyyy-mm-dd
 *
 * */

//usage: when storing shift in db
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

//preparing for authentication
//global oauth fn
//FIXME: having to call oauth each time. Authentication should fix.
function oauth(){
    $client = new GuzzleHttp\Client;

    try {
        $response = $client->post('http://odinlite.com/public/oauth/token', [
            'form_params' => [
                'client_id' => 2,
                // The secret generated when you ran: php artisan passport:install
                'client_secret' => 'OLniZWzuDJ8GSEVacBlzQgS0SHvzAZf1pA1cfShZ',
                'grant_type' => 'password',
                'username' => 'johnd@exampleemail.com',
                'password' => 'secret',
                'scope' => '*',
            ]
        ]);

        $auth = json_decode((string)$response->getBody());

       // $this->accessToken = $auth->access_token;
        return $auth->access_token;

    } catch (GuzzleHttp\Exception\BadResponseException $e) {
        echo $e;
        return view('admin_template');
    }
}
//
//function accessToken(){
//    return $this->accessToken;
//}


//    public function endDT($startTime, $duration)
//    {
//        $dt = new DateTime($startTime);//DateTime object
//        $interval = 'PT' . $duration . 'H';
//        $edt = $dt->add(new DateInterval($interval));
//        return $edt;
//    }
//
//    public function stringDate($dt)
//    {
//        $date = $dt->format('m/d/Y');
//        return $date;
//    }
//


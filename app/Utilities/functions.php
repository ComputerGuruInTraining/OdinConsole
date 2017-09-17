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

            $url = Config::get('constants.STANDARD_URL');
            $urlApi = Config::get('constants.API_URL');

            $response = $client->post($url.'oauth/token', [
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
            $responseUser = $client->get($urlApi.'user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $auth->access_token,
                ]
            ]);

            $user = json_decode((string)$responseUser->getBody());

            $responseStatus = $client->get($urlApi.'status/' . $user->company_id, [
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
                $responseRole = $client->get($urlApi.'user/role/' . $user->id, [
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

//if(! function_exists('pdfFile')) {
//    function pdfFile($id, $cases, $end)
//    {
//        $snappy = App::make('snappy.pdf');
//
//        $array = [1,2,3];
//
//        $arr = array();
//
//        for($i = 0; $i < count($array); $i++) {
//
//            $arr[$i] = "<h1>Bill</h1><p>Testing pdf" . $array[$i] . "</p>";
//
//        }
//
//        $html = $arr[0] + $arr[1];
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="file.pdf"');
//        echo $snappy->getOutputFromHtml($html);
//    }
//}

//creates pdf with value 0 on page only, no other text (same as $html += "")
//$array = [1,2,3];
//
//$arr = array();
//
//for($i = 0; $i < count($array); $i++) {
//
//    $arr[$i] = "<h1>Bill</h1><p>Testing pdf" . $array[$i] . "</p>";
//
//}
//
//$html = $arr[0] + $arr[1];
//header('Content-Type: application/pdf');
//header('Content-Disposition: attachment; filename="file.pdf"');
//echo $snappy->getOutputFromHtml($html);


//        }
////            foreach ($cases->get($index) as $item){
////        foreach ($cases->reportCaseNotes as $i => $item) {
//
////                dd($item->title, $index, $case, $item, $case[0]);
//                $html = "<h1>Bill</h1><p>You owe me money, dude ".$index."</p>";
//                dd($html);
//break;
////            }
//        }
//        $html = "";

//failing every time when just refresh page and when started from reports page through
            //error loading pdf, although pdf created
//            $array = [1,2,3];
//            foreach($array as $a) {
//
//                $html = "<h1>Bill</h1><p>You owe me money, dude " . $a . "</p>";
//
//                header('Content-Type: application/pdf');
//            header('Content-Disposition: attachment; filename="file.pdf"');
//            echo $snappy->getOutputFromHtml($html);
//            break;
//        }

        //error loading pdf, although pdf created
//        $snappy = App::make('snappy.pdf');
//
//        $array = [1,2,3];
//
//        foreach($array as $a) {
//
//            $html = "<h1>Bill</h1><p>You owe me money, dude " . $a . "</p>";
//
//            header('Content-Type: application/pdf');
//            header('Content-Disposition: attachment; filename="file.pdf"');
//            $pdf = $snappy->getOutputFromHtml($html);
//            break;
//        }
//        echo $pdf;
        //error loading pdf, although pdf created

//        $snappy = App::make('snappy.pdf');
//
//        $array = [1,2,3];
//
//        foreach($array as $a) {
//
//            $html = "<h1>Bill</h1><p>You owe me money, dude " . $a . "</p>";
//
////            dd($html);
//
//            break;
//        }
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="file.pdf"');
//        echo $snappy->getOutputFromHtml($html);



//        dd($cases->location->address);
//        $html = "<h1>Bill</h1><p>You owe me money, dude.".$cases->location->address."</p>";
//           <tr class='report-header-row'><td>Premise:</td></td><td class='report-header'>".$cases->location->address."</td></tr>";



//
//        <table class="col-md-12 margin-bottom">
//                    <tr><h4 id="report-date">{{$start}} - {{$end}}</h4></tr>
//                    <tr class="report-header-row"><td>Premise:</td></td><td class="report-header">{{$cases->location->address}}</td></tr>
//                    <tr class="report-header-row"><td>Hours Monitoring Premise:</td><td class="report-header"> {{$cases->reportCases->total_hours}}</td></tr>
//                    <tr class="report-header-row"><td>Guard Presence at Location:</td><td class="report-header">{{$cases->reportCases->total_guards}}</td></tr>
//                 </table>
//
//            <table class="table table-hover">
//                    {{--if there are case notes to report--}}
//                    <tr>
//                        {{--<th>Premise</th>--}}
//                        <th>Date</th>
//                        <th>Time</th>
//                        <th>Case Title</th>
//                        <th>Case Description</th>
//                        <th>Case Image</th>
//                        <th>Reporting Guard</th>
//                        <th>Case Id</th>
//                    </tr>
//                {{--Check to ensure there are case notes or else an error will be thrown--}}
//                    @if(count($cases->reportCaseNotes) != 0)
//
//        @foreach($groupCases as $index => $note)
//                            <tbody class="group-list">
//
//                            <tr>
//                            <td class="report-title">{{$index}}</td>
//                                <td></td>
//                                <td></td>
//                                <td></td>
//                                <td></td>
//                                <td></td>
//                                <td></td>
//                            </tr>
//    @foreach ($groupCases->get($index) as $item)
//                                <tr>
//                                    <td></td>
//                                    <td>{{$item->case_time}}</td>
//                                    <td>{{$item->title}}</td>
//                                    <td>{{$item->description}}</td>
//                                    <td>{{$item->img}}</td>
//                                    <td>{{$item->employee}}</td>
//                                    <td>{{$item->case_id}}</td>
//                                </tr>
//    @endforeach
//                            </tbody>
//    @endforeach
//
//                    @else
//                        <tr>
//                            <td></td>
//                            <td></td>
//                            <td></td>
//                            <td></td>
//                            <td></td>
//    @endif
//                </table>
//            </div>
//





/**PDF Trial and Error****/
//        echo  $snappy->generateFromHtml($html, '/bill-123.pdf');//provides a download file
//        $snappy->generateFromHtml($html, '/tmp/bill-123.pdf');
//        $snappy->generate('http://www.github.com', '/tmp/github.pdf');
//Or output:
//        return new Response(
//            200,
//            array(
//                'Content-Type'          => 'application/pdf',
//                'Content-Disposition'   => 'attachment; filename="file.pdf"'
//            )
//        );

//worked once, from reports page with fresh press of btn, rather than Ctrl R, note Report is not shown on page and no trailing backslash,
//summation: temperamental


//success: with backslash, without, with https, with http, 1(github no trailing backslash) 1(geo no trailing bs) 1 (github (note no fails with github yet??? or once??)), 1 (github),
//1 (https://github.com/) 1, (http://github.com'), 1(odinlite.com/public), 1(http://odinlitemgmt.azurewebsites.net)
//fail: 1(geo trailing bs) 1 (geo no trailing bs) 1 ctrl r/and straight link presses from a refreshed reports page
//1 ('http://www.australiangeographic.com.au/'), 1(odinlite.net),
//pdf success, specific page fail: 1(http://odinlitemgmt.azurewebsites.net/reports/24 DUE TO AUTH/LOGIN REQUIRED),
// 1('http://odinliteapi.azurewebsites.net/home' -> also login)



//trailing backslash gives error: This site can't be reach x 2
//works with github, but with 'http://www.australiangeographic.com.au/': This site can’t be reached
//        $snappy = App::make('snappy.pdf');
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="file.pdf"');
//        echo $snappy->getOutput('http://odinliteapi.azurewebsites.net/home');//provides a download file

//        //works with github
//        $snappy = App::make('snappy.pdf');
////        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="file.pdf"');
//        echo $snappy->getOutput('http://www.github.com');//provides a download file


//site can't be reached (also with echo getOutput)
//        $snappy = App::make('snappy.pdf');
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="file8.pdf"');
//        $snappy->getOutput('http://www.australiangeographic.com.au/');


//no download in downloads, but view report shows
//        $snappy = App::make('snappy.pdf');
//        $snappy->generate('http://www.github.com', '/tmp/github.pdf');




//using url
//works if called from ReportController, but not from button function,
//note: both odinlite.com/public and odinlite.com/public/password/confirm work, although styling improperly positioned

//        $url = 'http://odinlite.com/public';
////        $url = 'http://odinlite.com/public/password/confirm';
//        $url = Config::get('constants.STANDARD_URL');
//        $urlPdf = $url + 'password/confirm';
//        echo $snappy->getOutput('http://www.australiangeographic.com.au/');//provides a download file


//note: errors when trying to pdf a localhost view
//works with odinlite.com/public
//        $snappy = App::make('snappy.pdf');
////        $html = '<h1>Bill</h1><p>You owe me money, dude.</p>';
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="file6.pdf"');
//        echo $snappy->getOutput('http://odinlite.com/public');//provides a download fi



//using html

//        $html = '<h1>{{$entity}}</h1><p>You owe me money, {{$id}}.</p>';
//note: file name must be different eact time, or an error is thrown
//        $filePath = '/Users/bernie/Sites/www/OdinLiteConsole/storage/app/html5.pdf';

//        $snappy->generateFromHtml($html, $filePath);



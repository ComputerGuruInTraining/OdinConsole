<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
 * Make a request to the api endpoint
 */

$client = new GuzzleHttp\Client;
$clientGet = new GuzzleHttp\Client;

//use GuzzleHttp\RequestOptions::Headers;
use GuzzleHttp\Psr7\Request;

try {
    $response = $client->post('http://localhost:8000/oauth/token', [
        'form_params' => [
            'client_id' => 3,
            // The secret generated when you ran: php artisan passport:install
            'client_secret' => 'JfyQ4LuipiCwFoECBn5IdOT4JzhGxYsgCey75isV',
            'grant_type' => 'password',
            'username' => 'bernadettecar77@hotmail.com',
            'password' => 'password',
            'scope' => '*',
        ]
    ]);



    // You'd typically save this payload in the session
    $auth = json_decode( (string) $response->getBody() );
//    dd($auth);
//    dd($auth->access_token);



    $request = new Request('GET', 'http://localhost:8000/api/report-case-notes/list',
        [

        'Authorization' => 'Bearer '.$auth->access_token
        ]);

//    dd($request);
    //error in following code:
    $clientGet->send($request);
    dd($request);

//    $response = $client->request('GET', '/get', ('http://localhost:8000/api/report-case-notes/list', [
//        'headers' => [
//            'Content-Type', 'application/x-www-form-urlencoded',
//            'Accept' => 'application/json',
//            'Authorization' => 'Bearer '.$auth->access_token,
//        ]
//    ]);
//    dd($auth->access_token);
//    dd($request, $response);
//    $reports = json_decode( (string) $response->getBody() );
////    dd($report);
//    $reportList = "";
//    foreach ($reports as $report) {
//        $reportList .= "<li>{$report->location_id}</li>";
//    }
//
//    echo "<ul>{$reportList}</ul>";

} catch (GuzzleHttp\Exception\BadResponseException $e) {
    echo "Unable to retrieve access token.";
}



/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

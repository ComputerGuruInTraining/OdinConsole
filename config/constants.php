<?php
/**
 * Created by PhpStorm.
 * User: bernie
 * Date: 6/7/17
 * Time: 10:20 PM
 */

//WORK IN PROGRESS

//api routes
return [
//    'STANDARD_URL' => 'http://odinlite.net/',//invalid certificate chain when https, works when not
//    'API_URL' => 'http://odinlite.net/api/'
    'STANDARD_URL' => 'https://odinliteapitest.azurewebsites.net/',//works
    'API_URL' => 'https://odinliteapitest.azurewebsites.net/api/',//works even with https
    'ERROR_GENERIC' => 'Failed to load webpage.',
    'ERROR_SERVER' => 'Failed to load resource.',
//    todo: change company email then.....but first, trademarked? I recall odin.net is taken I believe for email domain
    'COMPANY_EMAIL' => 'admin@odinlite.net',
    'COMPANY_NAME' => 'Odin Case Management',
    'ERROR_UPDATE' => 'Unexpected error updating details',
    'INTERNET_ERROR' => 'Internet Connection Error',
    'CONN_SERVER_ERROR' => 'Experiencing error when connecting to server',

    /*****user roles******/
    'ROLE_1' => 'Manager',
    'ROLE_2' => 'Investigator'
];
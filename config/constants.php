<?php
/**
 * Created by PhpStorm.
 * User: bernie
 * Date: 6/7/17
 * Time: 10:20 PM
 */

//api routes
return [

//    'STANDARD_URL' => 'https://odinliteapi.azurewebsites.net/',
//    'API_URL' => 'https://odinliteapi.azurewebsites.net/api/',
    /*****Api*****/
    'STANDARD_URL' => 'https://odinliteapitest.azurewebsites.net/',
    'API_URL' => 'https://odinliteapitest.azurewebsites.net/api/',

    /*****Company Specs******/
    'COMPANY_EMAIL' => 'admin@odinlite.net',
    'COMPANY_EMAIL_SUPPORT' => 'support@odincasemanagement.com',
    'COMPANY_NAME' => 'ODIN Case Management',
    'COMPANY_NICKNAME' => 'ODIN',
    'TEAM' => 'ODIN Team',
    'LOGO' => asset("/bower_components/AdminLTE/dist/img/odinLogoCurr.png"),

    /****Error Msgs*****/
    'ERROR_UPDATE' => 'Unexpected error updating details',
    'INTERNET_ERROR' => 'Internet Connection Error',
    'CONN_SERVER_ERROR' => 'Experiencing error when connecting to server',
    'ERROR_GENERIC' => 'Failed to load webpage.',
    'ERROR_SERVER' => 'Failed to load resource.',

    /*****user roles******/
    'ROLE_1' => 'Manager',
    'ROLE_2' => 'Investigator',

    /*****stripe*****/
    'STRIPE_TEST_KEY' => 'pk_test_u5hJw0nEAL2kgix2Za91d3cV',
    'STRIPE_KEY' => 'pk_live_oQQ02SuVrn0UHTOnYIKsizcV',

    /****subscriptions****/
    'AMOUNT_M1' => 29,
    'AMOUNT_M2' => 59,
    'AMOUNT_M3' => 99,
    'AMOUNT_Q1' => 19,
    'AMOUNT_Q2' => 39,
    'AMOUNT_Q3' => 69,
    'DISCOUNT1' => 34,
    'DISCOUNT2' => 34,
    'DISCOUNT3' => 30,


];
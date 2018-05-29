<?php
/**
 * Created by PhpStorm.
 * User: bernie
 * Date: 6/7/17
 * Time: 10:20 PM
 */

//api routes
return [

    // 'STANDARD_URL' => 'https://odinliteapi.azurewebsites.net/',
    // 'API_URL' => 'https://odinliteapi.azurewebsites.net/api/',
    /*****Api*****/
   'STANDARD_URL' => 'https://odinliteapitest.azurewebsites.net/',
   'API_URL' => 'https://odinliteapitest.azurewebsites.net/api/',

    /*****Company Specs******/
    'COMPANY_EMAIL' => 'admin@odinlite.net',
    'COMPANY_EMAIL_SUPPORT' => 'support@odincasemanagement.com',
    'COMPANY_NAME' => 'ODIN Case Management',
    'COMPANY_NICKNAME' => 'ODIN',
    'TEAM' => 'ODIN Team',

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
//    'STRIPE_TEST_KEY' => 'pk_test_u5hJw0nEAL2kgix2Za91d3cV',
//    'STRIPE_KEY' => 'pk_live_oQQ02SuVrn0UHTOnYIKsizcV',

    /****subscriptions****/
    'AMOUNT_M1' => 29,
    'AMOUNT_M2' => 59,
    'AMOUNT_M3' => 99,
    'AMOUNT_Y1' => 19,
    'AMOUNT_Y2' => 39,
    'AMOUNT_Y3' => 69,
    'DISCOUNT1' => 34,
    'DISCOUNT2' => 34,
    'DISCOUNT3' => 30,
//    'CONVERSION_RATE' => 1.293137, /***USD TO AUD CONVERSION**/

    //stripe plan ids

    /*monthly*/
    'PLAN1_MONTHLY' => 'plan_CXjmOc1d4APdQ1',//$29/mth
    'PLAN2_MONTHLY' => 'plan_CXjnfBY2GonO8J',//$59/mth
    'PLAN3_MONTHLY' => 'plan_CXjnJMfqW8hTnH',//$99/mth
    'PLAN4_MONTHLY' => '',

    /*yearly*/
    'PLAN1_YEARLY' => 'plan_CXjqEb6lGsQXcU',//$228/yr
    'PLAN2_YEARLY' => 'plan_CXjqkgBvAAikFU',//$468/yr
    'PLAN3_YEARLY' => 'plan_CXjrylVJoFY2It',//$828/yr
    'PLAN4_YEARLY' => '',

    /*Testing only*/
    'TEST_PLAN1_MONTHLY' => 'plan_CXk0vEO3OII8ZU',
    'TEST_PLAN1_YEARLY' => 'plan_CXk0hfbFwLLvVd',

    /*Error Codes*/
    'EDIT_PRIMARY_CONTACT_ERROR' => 'EPC',
    'NEW_SUBSCRIPTION_ERROR' => 'NS',
    //usage for EPCNS: if the original subscription was cancelled and the new subscription and subsequently resume subscription failed. and didn't revert to original primary contact~
    'EDIT_PRIMARY_CONTACT_NEW_SUBSCRIPTION' => 'EPCNS',
    //usage for EPCCS: subscription wasn't transferred and then didn't revert back to original primary contact. original subscription is still intact.
    'EDIT_PRIMARY_CONTACT_CANCEL_SUBSCRIPTION' => 'EPCCS',

];
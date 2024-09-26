<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->post('customer', [
    'as' => 'customer', 'uses' => 'CustomerController@store'
]);

$router->get('/', function () use ($router) {

    $wallet = Wallet::customerRegistration('1234567890', 'John Doe', 'luis.alvarez00@usc.edu.co', '3218452593');

//    $result = $client->customerRegistration('1234567890', 'John Doe', 'luis.alvarez00@usc.edu.co', '3218452593');

    dd($wallet);
//
//
//    return $router->app->version();
});

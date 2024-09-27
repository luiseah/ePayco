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


$router->post('customers', [
    'as' => 'customers',
    'uses' => 'CustomerController@store'
]);

$router->group(['prefix' => 'wallets'], function () use ($router) {
    $router->post('/recharges', [
        'as' => 'recharges',
        'uses' => 'WalletController@recharge'
    ]);

    $router->post('/payments', [
        'as' => 'payments',
        'uses' => 'WalletController@payment'
    ]);

    $router->post('/payments/confirm', [
        'as' => 'payments-confirm',
        'uses' => 'WalletController@paymentConfirm'
    ]);

    $router->get('/balance-inquiry', [
        'as' => 'balance-inquiry',
        'uses' => 'WalletController@balanceInquiry'
    ]);
});
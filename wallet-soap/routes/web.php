<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
//    dd(SoapWrapper::services());
    return view('welcome');
});
//
//Route::get('/wallet/wsdl',[\App\Http\Controllers\SoapController::class,'wsdlAction'])
//    ->name('soap-wsdl');
//
//
//Route::post('/wallet/server',[\App\Http\Controllers\SoapController::class,'serverAction'])
//    ->name('soap-server');



//Route::post('/', function () {
//    dd(SoapWrapper::services());
//    return view('welcome');
//});

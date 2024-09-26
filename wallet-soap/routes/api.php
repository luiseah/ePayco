<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::get('/wallet/wsdl',[\App\Http\Controllers\SoapController::class,'wsdlAction'])
    ->name('soap-wsdl');


Route::post('/wallet/server',[\App\Http\Controllers\SoapController::class,'serverAction'])
    ->name('soap-server');
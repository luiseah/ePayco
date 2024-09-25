<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::any('{any}', function ($any) {
    // Imprimir la ruta solicitada
    return "Kitchen Ruta solicitada: " . $any;
})->where('any', '.*');

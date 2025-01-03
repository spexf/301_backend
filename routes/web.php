<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('genPass', function (){
    return Hash::make('12345678');
});
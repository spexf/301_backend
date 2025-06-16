<?php

use App\Models\Item;
use App\Models\User;
use App\Mail\sendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('genPass', function () {
    return Hash::make('12345678');
});

Route::get('sendmail', function () {
    return Item::get();
});

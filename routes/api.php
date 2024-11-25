<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Mail\sendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('sendMail', function () {
    return dd(Mail::to('ath.thoriq1103@gmail.com')->send(new sendEmail()));
});

Route::middleware(['auth:sanctum', 'role:user,api'])->group(function () {
    // GET
    Route::get('/items', [ItemController::class, 'getAllItems'])->name('get-items');
    Route::get('/items/verified', [ItemController::class])->name('get-verified-items');
    Route::get('/items/{filterType}/{filterStatus}/{filterTime}', [ItemController::class, 'getMyFilteredItem'])->name('get-my-filtered-items');
    Route::get('/tesHit', function () {
        return auth()->user()->getRoleNames();
    });

    // POST
    Route::post('/items/create', [ItemController::class, 'storeItem'])->name('store-item');
    Route::post('/auth/validate', [AuthController::class, 'authValidate']);

    // DELETE


    // PATCH
});
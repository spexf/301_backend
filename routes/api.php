<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/items', [ItemController::class, 'getAllItems'])->name('get-items');
    Route::post('/items/create', [ItemController::class, 'storeItem'])->name('store-item');
    Route::get('/tesHit', function () {
        return auth()->user();
    });
});
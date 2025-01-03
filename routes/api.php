<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware(['auth:sanctum', 'role:admin,api'])->group(function () {
    // GET
    Route::get('/admin/items', [ItemController::class, 'getFrontEndData']);
    Route::get('/admin/items/{id}', [ItemController::class, 'getItemDetails']);
    Route::get('/admin/users', action: [UserController::class, 'getUser']);
    // PUT
    Route::put('/admin/items/{id}/{status}', [ItemController::class, 'changeStatus']);
    Route::put('/admin/users/{id}/ban', [UserController::class, 'banUser']);
    Route::put('/admin/users/{id}/unban', [UserController::class, 'unbanUser']);
    // PATCH
    Route::patch('/admin/item/verify/{id}', [ItemController::class, 'verifyItem']);
    Route::patch('/admin/item/cancel/{id}', [ItemController::class, 'cancelItem']);
    // DELETE
    Route::delete('/admin/users/{id}', [UserController::class, 'deleteUser']);

});

Route::middleware(['auth:sanctum', 'role:user|admin,api'])->group(function () {
    // GET


    Route::get('/items/details/{id}', [ItemController::class, 'getItemDetails']);
    Route::get('/items/images/{type}/{name}', [ItemController::class, 'getImage'])->name('get-item-image');
    Route::get('/items', [ItemController::class, 'getAllItems'])->name('get-items');
    Route::get('/items/verified/{type}', [ItemController::class, 'getAllVerifiedItem'])->name('get-verified-items');
    Route::get('/items/{filterType}/{filterStatus}/{filterTime}', [ItemController::class, 'getMyFilteredItem'])->name('get-my-filtered-items');
    Route::get('/items/myUpload', [ItemController::class, 'getMyItems']);
    Route::get('/items/finished', [ItemController::class, 'getFinishedItem']);
    // POST
    Route::post('/auth/validate', [AuthController::class, 'authValidate']);
    Route::post('/items/create', [ItemController::class, 'storeItem'])->name('store-item');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/transaction/create', [TransactionController::class, 'storeTransactionItem']);

    // DELETE


    // PATCH
});
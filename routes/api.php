<?php

use App\Http\Controllers\Admins\ProductController;
use App\Http\Controllers\Admins\SizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('Admin/Product')->group(function () {
    Route::get('/View', [ProductController::class, 'View']);
});


Route::prefix('Admin/Size')->group(function () {
    Route::get('/View', [SizeController::class, 'View']);
    Route::delete('/Delete/{id}', [SizeController::class, 'Delete']);
    Route::post('Add', [SizeController::class, 'Add']);
    Route::put('Update/{id}', [SizeController::class, 'Update']);
});

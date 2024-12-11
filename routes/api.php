<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/store', [UserController::class, 'store']);
Route::get('user/get', [UserController::class, 'index']);
Route::get('user/get/{id}', [UserController::class, 'show']);
Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
Route::put('user/update/{id}', [UserController::class, 'update']);
Route::patch('user/change-password/{id}', [UserController::class, 'changePassword']);






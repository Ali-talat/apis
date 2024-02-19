<?php

use App\Http\Controllers\admin\AuthController;
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
Route::post('/login',[AuthController::class,'login']);
Route::post('/regester',[AuthController::class,'regester']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('api');
Route::post('/profile',[AuthController::class,'profile'])->middleware('api');
Route::post('/refresh',[AuthController::class,'refresh'])->middleware('api');


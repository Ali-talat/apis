<?php

use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\user\AuthController as UserAuthController;
use App\Http\Controllers\post\PostController;
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
Route::prefix('user')->group(function(){

Route::post('/login',[UserAuthController::class,'login']);
Route::post('/regester',[UserAuthController::class,'regester']);
Route::post('/logout',[UserAuthController::class,'logout'])->middleware('api');
Route::post('/profile',[UserAuthController::class,'profile'])->middleware('api');
Route::post('/refresh',[UserAuthController::class,'refresh'])->middleware('api');
});

Route::prefix('admin')->group(function(){

    Route::post('/login',[AuthController::class,'login']);
    Route::post('/regester',[AuthController::class,'regester']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:admin');
    Route::post('/profile',[AuthController::class,'profile'])->middleware('auth:admin');
    Route::post('/refresh',[AuthController::class,'refresh'])->middleware('auth:admin');
    });


Route::middleware(['auth:api'])->prefix('post')->group(function () {
Route::post('/index',[PostController::class,'index']);
Route::post('/create',[PostController::class,'create']);
Route::post('/store',[PostController::class,'store']);
Route::post('/edit/{id}',[PostController::class,'edit']);
Route::post('/update/{id}',[PostController::class,'update']);
Route::post('/delete/{id}',[PostController::class,'delete']);
    
});
 


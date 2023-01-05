<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
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

Route::middleware('auth:sanctum')->get('v1/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function(){
    Route::post('register', [AuthController::class, 'store']);
    Route::post('login', [AuthController::class, 'index']);

    // Route::middleware(['super-admin'/*, 'permission:create-users'*/])->group(function () {
    //     // Routes that require the "superadmin" role and the "create-users" permission...
    //     Route::post('create-users', [AuthController::class,'store']);
    //     Route::post('create-users-admin', [AuthController::class,'storeAdmin']);
    // });
    //Route::middleware('auth:sanctum')->group(function () {
        //Route::resource('categories', CategoryController::class)->middleware('super-admin');
        Route::get('categories', [CategoryController::class, 'index'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
   // });
    //Route::resource('categories', CategoryController::class);//->middleware('super-admin');
});


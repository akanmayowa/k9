<?php

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TarrrifController;


// Route::post("register", [UserController::class, "register"]);


//Route::post("login", [UserController::class, "login"]);
//Route::post('login', 'Auth\LoginController@login');

// Route::group(['prefix' => 'api', 'middleware' => ['auth']], function(){
//     Route::get('tarriff',[TarriffQuotationController::class, '']);
//     Route::post("logout", [UserController::class, "logout"]);
// });


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("tarriff", [TarrrifController::class, "getTarriff"]);

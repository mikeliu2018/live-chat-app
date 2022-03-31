<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function (Illuminate\Routing\Router $router) {
    // Route::put('/login', [AuthController::class, 'login']);
    // Route::post('/register', [AuthController::class, 'register']);
    Route::post('/vendor-signin', [AuthController::class, 'vendorSignin']);
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'auth'
], function (Illuminate\Routing\Router $router) {
    Route::put('/logout', [AuthController::class, 'logout']);
    Route::put('/refresh', [AuthController::class, 'refresh']);
    // Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'user'
], function (Illuminate\Routing\Router $router) {    
    Route::get('/profile', [UserController::class, 'profile']);
});


Route::get('/test/load_log', function(Request $request){
    
});

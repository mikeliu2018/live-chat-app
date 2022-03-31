<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    if (View::exists('app'))
    {
        return View::make('app');
    }
});

Route::get('/api-docs', function () {
    // return view('welcome');
    if (View::exists('api-docs'))
    {
        return View::make('api-docs');
    }
});


Route::get('/about', function () {
    // return view('welcome');
    if (View::exists('about'))
    {
        return View::make('about');
    }
    return view('welcome');
});
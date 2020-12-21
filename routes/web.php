<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('login', function () {
    return view('login');
});
Route::post('users', function () {
    return false;
});
Route::get('create_user', function () {
    return false;
});
Route::post('menu', function () {
    return view('menu');
});
Route::get('jason', function () {
    return view('jason');
});

Route::post('users', 'App\Http\Controllers\ConnectController@login');
Route::post('create_user', 'App\Http\Controllers\ConnectController@create');
Route::post('profile', 'App\Http\Controllers\ConnectController@showprofile');
Route::post('history', 'App\Http\Controllers\ConnectController@history');
Route::get('listcarcare', 'App\Http\Controllers\ConnectController@listcarcare');
Route::post('car_member', 'App\Http\Controllers\ConnectController@car_member');
Route::post('attribute', 'App\Http\Controllers\ConnectController@attribute');
Route::post('conform', 'App\Http\Controllers\ConnectController@conform');

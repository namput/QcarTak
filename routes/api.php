<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('longdo','App\Http\Controllers\ApiController');
Route::post('login','App\Http\Controllers\ApiController@login');
Route::post('create','App\Http\Controllers\ApiController@create');
Route::post('showprofile','App\Http\Controllers\ApiController@showprofile');
Route::post('updatename','App\Http\Controllers\ApiController@updatename');
Route::get('showcar','App\Http\Controllers\ApiController@showcar');
Route::get('showcolor','App\Http\Controllers\ApiController@showcolor');
Route::post('carmember','App\Http\Controllers\ApiController@carmember');
Route::post('history','App\Http\Controllers\ApiController@history');
Route::post('listcarcare', 'App\Http\Controllers\ApiController@listcarcare');
Route::post('listcarmember','App\Http\Controllers\ApiController@listcarmember');
Route::post('attribute','App\Http\Controllers\ApiController@attribute');
Route::post('queue','App\Http\Controllers\ApiController@queue');
Route::post('checkstatus','App\Http\Controllers\ApiController@checkstatus');
Route::post('checkqueue','App\Http\Controllers\ApiController@checkqueue');
Route::post('updatequeue','App\Http\Controllers\ApiController@updatequeue');
Route::post('RatingReview','App\Http\Controllers\ApiController@RatingReview');
